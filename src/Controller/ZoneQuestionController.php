<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Service\GameLogicService;
use App\Service\PlayerService;
use App\Service\ZoneProgressionService;
use App\Repository\QuestionRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/game/zone', name: 'game_zone_')]
#[IsGranted('ROLE_USER')]
class ZoneQuestionController extends AbstractController
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

    /**
     * Afficher les questions d'une zone
     */
    #[Route('/{zoneId}', name: 'show', methods: ['GET'])]
    public function showZone(int $zoneId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);
        
        // Vérifier le game over
        if ($progress->isGameOver() || $progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_index');
        }

        $zone = $this->zoneRepo->find($zoneId);
        if (!$zone || !$zone->isActive()) {
            $this->addFlash('error', 'Zone non disponible');
            return $this->redirectToRoute('game_index');
        }

        // Vérifier si la zone est débloquée
        $zoneProgress = $this->zoneProgression->getZoneProgress($player, $zone);
        if (!$zoneProgress || $zoneProgress->isLocked()) {
            $this->addFlash('error', 'Cette zone est verrouillée');
            return $this->redirectToRoute('game_index');
        }

        // Obtenir toutes les questions de la zone
        $allQuestions = $this->questionRepo->findBy(
            ['zone' => $zone, 'isActive' => true],
            ['displayOrder' => 'ASC']
        );
        
        $totalQuestions = count($allQuestions);
        
        // Obtenir les questions non répondues
        $questions = $this->gameLogic->getUnansweredQuestions($progress, $zone);
        
        if (empty($questions)) {
            // La zone est terminée, la marquer comme complétée
            if (!$zoneProgress->isCompleted()) {
                $this->zoneProgression->completeZone($player, $zone);
                $bonusPoints = (int) floor($zone->getMinPointsToUnlock() / 5);
                $progress->addPoints($bonusPoints);
                $this->em->flush();
                $this->addFlash('success', sprintf('Zone « %s » complétée! +%d points bonus', 
                    $zone->getName(), 
                    $bonusPoints
                ));
            }

            // Tenter d'aller vers la zone suivante automatiquement
            $nextZone = $this->zoneRepo->findNextZone($zone);
            if ($nextZone && $nextZone->isActive()) {
                return $this->redirectToRoute('game_zone_show', ['zoneId' => $nextZone->getId()]);
            }

            // Pas de zone suivante -> fin du jeu
            $this->addFlash('info', 'Toutes les zones terminées');
            return $this->redirectToRoute('game_index');
        }

        // Sélectionner une question aléatoire parmi les restantes
        $currentQuestion = $questions[array_rand($questions)];

        return $this->render('game/zone_question.html.twig', [
            'zone' => $zone,
            'zoneProgress' => $zoneProgress,
            'question' => $currentQuestion,
            'player' => $player,
            'progress' => $progress,
            'remainingQuestions' => count($questions),
            'totalQuestions' => $totalQuestions,
        ]);
    }

    /**
     * Répondre à une question de zone via AJAX
     */
    #[Route('/{zoneId}/answer', name: 'answer', methods: ['POST'])]
    public function answerQuestion(int $zoneId, Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['error' => 'Invalid request'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
            $progress = $this->gameLogic->getOrCreateProgress($player);
            
            // Vérifier le game over
            if ($progress->isGameOver() || $progress->getHearts() <= 0) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Game Over - retour au menu',
                    'gameOver' => true
                ]);
            }
            
            $zone = $this->zoneRepo->find($zoneId);
            if (!$zone) {
                return new JsonResponse(['error' => 'Zone not found'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            $questionId = $data['questionId'] ?? null;
            $isCorrect = $data['isCorrect'] ?? false;

            if (!$questionId) {
                return new JsonResponse(['error' => 'Missing question ID'], Response::HTTP_BAD_REQUEST);
            }

            $question = $this->questionRepo->find($questionId);
            if (!$question) {
                return new JsonResponse(['error' => 'Question not found'], Response::HTTP_NOT_FOUND);
            }

            // Traiter la réponse via GameLogicService
            $result = $this->gameLogic->processQuestionAnswer($progress, $question, $isCorrect);

            // Mettre à jour la progression de la zone TOUJOURS
            $zoneProgress = $this->zoneProgression->getZoneProgress($player, $zone);
            if (!$zoneProgress) {
                $zoneProgress = $this->zoneProgression->getOrCreateZoneProgress($player, $zone);
            }
            
            $zoneProgress->incrementQuestionsAnswered();
            if ($isCorrect) {
                $zoneProgress->incrementQuestionsCorrect();
                $zoneProgress->addZoneScore($question->getRewardPoints());
            } else {
                // Pénalité pour mauvaise réponse
                $zoneProgress->addZoneScore(-max(0, $question->getPenaltyPoints()));
            }
            
            $this->em->flush();
            
            // Ajouter les infos de zone au résultat
            $allQuestions = $this->questionRepo->findBy(['zone' => $zone, 'isActive' => true]);
            $totalQuestions = count($allQuestions);
            
            $result['zoneProgress'] = [
                'questionsAnswered' => $zoneProgress->getQuestionsAnswered(),
                'questionsCorrect' => $zoneProgress->getQuestionsCorrect(),
                'progressPercentage' => $zoneProgress->getProgressPercentageFromTotal($totalQuestions),
                'zoneScore' => $zoneProgress->getZoneScore(),
                'isCompleted' => $zoneProgress->isFullyAnswered($totalQuestions),
            ];
            
            // Si la zone est complétée, la marquer comme telle et débloquer la suivante
            if ($result['zoneProgress']['isCompleted'] && !$zoneProgress->isCompleted()) {
                $this->zoneProgression->completeZone($player, $zone);
                
                // Ajouter bonus points
                $bonusPoints = (int) floor($zone->getMinPointsToUnlock() / 5);
                $progress->addPoints($bonusPoints);
                $this->em->flush();
                
                // Trouver la zone suivante pour redirection
                $nextZone = $this->zoneRepo->findNextZone($zone);
                $result['zoneProgress']['isCompleted'] = true;
                $result['zoneProgress']['nextZoneId'] = $nextZone?->getId();
                $result['zoneProgress']['hasNextZone'] = $nextZone !== null;
                $result['zoneProgress']['bonusPoints'] = $bonusPoints;
            }
            
            return new JsonResponse($result);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{zoneId}/complete', name: 'complete', methods: ['POST'])]
    public function completeZone(int $zoneId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $zone = $this->zoneRepo->find($zoneId);

        if (!$zone) {
            $this->addFlash('error', 'Zone non trouvée');
            return $this->redirectToRoute('game_index');
        }

        // Marquer la zone comme complétée
        $this->zoneProgression->completeZone($player, $zone);

        // Bonus points pour avoir complété la zone
        $progress = $this->gameLogic->getOrCreateProgress($player);
        $bonusPoints = (int) floor($zone->getMinPointsToUnlock() / 5);
        $progress->addPoints($bonusPoints);
        $this->em->flush();

        $this->addFlash('success', sprintf(
            'Zone « %s » complétée! +%d points bonus',
            $zone->getName(),
            $bonusPoints
        ));

        return $this->redirectToRoute('game_index');
    }

    /**
     * Continuer vers la prochaine zone ou revenir au menu
     * Route intelligente pour le bouton "Continuer"
     */
    #[Route('/continue', name: 'continue', methods: ['GET'])]
    public function continueAdventure(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        if ($progress->isGameOver() || $progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_index');
        }

        // Récupérer la zone actuellement jouée (non terminée)
        $currentZone = $this->zoneProgression->getCurrentPlayableZone($player);

        if ($currentZone && !$currentZone->isActive()) {
            // Zone n'existe plus ou n'est pas active
            return $this->redirectToRoute('game_index');
        }

        if ($currentZone) {
            // Continuer dans la zone actuelle
            return $this->redirectToRoute('game_zone_show', ['zoneId' => $currentZone->getId()]);
        }

        // Pas de zone en cours, retourner au menu
        return $this->redirectToRoute('game_index');
    }
}
