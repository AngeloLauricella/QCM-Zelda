<?php

namespace App\Controller;

use App\Service\GameLogicService;
use App\Service\PlayerService;
use App\Service\ZoneProgressionService;
use App\Repository\QuestionRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/game', name: 'game_')]
#[IsGranted('ROLE_USER')]
class GameController extends AbstractController
{
    public function __construct(
        private GameLogicService $gameLogic,
        private PlayerService $playerService,
        private ZoneProgressionService $zoneProgression,
        private QuestionRepository $questionRepo,
        private ZoneRepository $zoneRepo,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        // Détecter le game over sans le réinitialiser automatiquement
        $isGameOver = $progress->isGameOver() || $progress->getHearts() <= 0;
        $hasStarted = $progress->hasStarted() && !$isGameOver;
        
        // Obtenir les zones débloquées et complétées avec leur progression
        $unlockedZones = [];
        $completedZones = [];
        $currentZone = null;
        
        if ($hasStarted) {
            $currentZone = $this->zoneProgression->getCurrentPlayableZone($player);
            
            $unlockedZonesData = $this->zoneProgression->getUnlockedZones($player);
            $completedZonesData = $this->zoneProgression->getCompletedZones($player);
            
            // Enrichir avec les données de progression
            foreach ($unlockedZonesData as $zone) {
                $zoneProgress = $this->zoneProgression->getZoneProgress($player, $zone);
                $unlockedZones[] = [
                    'zone' => $zone,
                    'progress' => $zoneProgress,
                    'isCurrent' => $currentZone && $currentZone->getId() === $zone->getId(),
                ];
            }
            
            foreach ($completedZonesData as $zone) {
                $zoneProgress = $this->zoneProgression->getZoneProgress($player, $zone);
                $completedZones[] = [
                    'zone' => $zone,
                    'progress' => $zoneProgress,
                ];
            }
        }

        return $this->render('game/index.html.twig', [
            'player' => $player,
            'hearts' => $progress->getHearts(),
            'maxHearts' => 5,
            'points' => $progress->getPoints(),
            'unlockedZones' => $unlockedZones,
            'completedZones' => $completedZones,
            'currentZone' => $currentZone,
            'isGameOver' => $isGameOver,
            'gameOverReason' => $progress->getGameOverReason(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function newAdventure(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $this->gameLogic->startNewAdventure($player);
        return $this->redirectToRoute('game_start');
    }

    #[Route('/start', name: 'start', methods: ['GET'])]
    public function startAdventure(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        // If game is over, redirect to index to show game over screen
        if ($progress->isGameOver() || $progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_index');
        }

        $zone = $this->zoneRepo->find($progress->getCurrentZoneId());
        if ($zone && $zone->isActive() && $this->gameLogic->isZoneUnlocked($progress, $zone)) {
            return $this->redirectToRoute('game_zone', ['zoneId' => $zone->getId()]);
        }

        $firstZone = $this->zoneRepo->findFirstActiveZone();
        if ($firstZone) {
            return $this->redirectToRoute('game_zone', ['zoneId' => $firstZone->getId()]);
        }

        $this->addFlash('error', 'Aucune zone disponible');
        return $this->redirectToRoute('game_index');
    }

    #[Route('/restart', name: 'restart', methods: ['POST'])]
    public function restart(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);
        
        // Seulement si vraiment en game over
        if ($progress->isGameOver() || $progress->getHearts() <= 0) {
            // 1. ENREGISTRER LE SCORE FINAL
            $finalPoints = $progress->getPoints();
            $shopPoints = (int) floor($finalPoints / 10); // 1/10 des points de jeu
            
            // ✅ CRÉER ET PERSISTER L'ENTITÉ SCORE
            // Vérifier s'il y a déjà un score pour ce joueur
            $existingScore = $player->getScoreEntity();
            if (!$existingScore) {
                // Créer un nouveau score
                $score = new \App\Entity\Score();
                $score->setPlayer($player);
                $score->setValue($finalPoints);
                $player->setScoreEntity($score);
                $this->em->persist($score);
            } else {
                // Mettre à jour le score existant
                $existingScore->setValue($finalPoints);
            }
            
            // Ajouter les points boutique au joueur
            $player->addShopPoints($shopPoints);
            
            // 2. NETTOYER ET RÉINITIALISER
            $this->gameLogic->cleanCompletedQuestions($progress);
            $progress->reset();
            
            // Définir la première zone
            $firstZone = $this->zoneRepo->findFirstActiveZone();
            if ($firstZone) {
                $progress->setCurrentZoneId($firstZone->getId());
            }
            
            // ✅ FLUSH OBLIGATOIRE POUR PERSISTER EN BD
            $this->em->flush();
            
            $this->addFlash('success', sprintf(
                '🎮 Score enregistré: %d points! Tu as gagné %d points boutique 💎',
                $finalPoints,
                $shopPoints
            ));
        }
        
        return $this->redirectToRoute('game_index');
    }

    #[Route('/zone/{zoneId}', name: 'zone',requirements: ['zoneId' => '\d+'], methods: ['GET'])]
    public function zone(int $zoneId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        if ($progress->isGameOver() || $progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_index');
        }

        $zone = $this->zoneRepo->find($zoneId);
        if (!$zone || !$zone->isActive()) {
            $this->addFlash('error', 'Cette zone n\'existe pas ou n\'est pas active');
            return $this->redirectToRoute('game_index');
        }

        if (!$this->gameLogic->isZoneUnlocked($progress, $zone)) {
            $this->addFlash('warning', 'Cette zone n\'est pas encore déverrouillée');
            return $this->redirectToRoute('game_index');
        }

        $progress->setCurrentZoneId($zoneId);
        $this->em->flush();

        // Récupérer toutes les questions non répondues de cette zone
        $questions = $this->gameLogic->getUnansweredQuestions($progress, $zone);
        
        // Si aucune question disponible, retour au menu
        if (empty($questions)) {
            $this->addFlash('success', '🎉 Toutes les questions de cette zone sont complétées!');
            return $this->redirectToRoute('game_index');
        }
        
        // Sélectionner UNE question aléatoire
        $randomQuestion = $questions[array_rand($questions)];
        
        // Rediriger directement vers cette question
        return $this->redirectToRoute('game_play_question', ['questionId' => $randomQuestion->getId()]);
    }

    #[Route('/question/{questionId}', name: 'play_question', methods: ['GET'])]
    public function playQuestion(int $questionId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        // Protection: si le game est over, retourner au menu
        if ($progress->isGameOver() || $progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_index');
        }

        $question = $this->questionRepo->find($questionId);
        if (!$question || !$question->isActive()) {
            return $this->redirectToRoute('game_index');
        }

        $zone = $question->getZone();
        if (!$zone || !$zone->isActive()) {
            return $this->redirectToRoute('game_index');
        }

        if (!$this->gameLogic->isZoneUnlocked($progress, $zone)) {
            $this->addFlash('warning', 'Cette zone n\'est pas encore déverrouillée');
            return $this->redirectToRoute('game_index');
        }

        $progress->setCurrentZoneId($zone->getId());
        $this->em->flush();

        return $this->render('game/question.html.twig', [
            'question' => $question,
            'zone' => $zone,
            'player' => $player,
            'hearts' => $progress->getHearts(),
            'points' => $progress->getPoints(),
        ]);
    }

    #[Route('/question/{questionId}/answer', name: 'answer_question', methods: ['POST'])]
    public function answerQuestion(int $questionId, Request $request): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        // Protection: si le game est over, retourner au menu
        if ($progress->isGameOver() || $progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_index');
        }

        $question = $this->questionRepo->find($questionId);
        if (!$question || !$question->isActive()) {
            $this->addFlash('error', 'Question introuvable');
            return $this->redirectToRoute('game_index');
        }

        if (!$this->gameLogic->canPlayerAnswerQuestion($progress, $question)) {
            $this->addFlash('warning', 'Cette question a déjà été répondue');
            $zone = $question->getZone();
            return $this->redirectToRoute('game_zone', ['zoneId' => $zone->getId()]);
        }

        $userAnswer = $request->request->get('answer');
        $isCorrect = $question->isCorrectAnswer($userAnswer);
        $result = $this->gameLogic->processQuestionAnswer($progress, $question, $isCorrect);

        if ($result['isGameOver']) {
            $this->addFlash('error', '💀 ' . $result['message']);
            return $this->redirectToRoute('game_index');
        }

        // Message de feedback
        if ($isCorrect) {
            $this->addFlash('success', '✅ ' . $result['message']);
        } else {
            $this->addFlash('warning', '❌ ' . $result['message']);
        }

        $zone = $question->getZone();
        // Retourner à la zone qui affichera automatiquement la prochaine question
        return $this->redirectToRoute('game_zone', ['zoneId' => $zone->getId()]);
    }

    #[Route('/over', name: 'over', methods: ['GET'])]
    public function gameOver(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        return $this->render('game/game_over.html.twig', [
            'player' => $player,
            'hearts' => $progress->getHearts(),
            'points' => $progress->getPoints(),
        ]);
    }
}
