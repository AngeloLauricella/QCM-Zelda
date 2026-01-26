<?php

namespace App\Controller;

use App\Service\GameLogicService;
use App\Service\PlayerService;
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

        $hasStarted = $progress->hasStarted();
        $unlockedZones = $hasStarted ? $this->gameLogic->getUnlockedZones($progress) : [];

        return $this->render('game/index.html.twig', [
            'player' => $player,
            'hearts' => $progress->getHearts(),
            'maxHearts' => 5,
            'points' => $progress->getPoints(),
            'unlockedZones' => $unlockedZones,
            'isGameOver' => $progress->getHearts() <= 0,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function newAdventure(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $this->gameLogic->startNewAdventure($player);
        return $this->redirectToRoute('game_index');
    }

    #[Route('/start', name: 'start', methods: ['GET'])]
    public function startAdventure(): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

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

    #[Route('/zone/{zoneId}', name: 'zone', methods: ['GET'])]
    public function zone(int $zoneId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        if ($progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_over');
        }

        $zone = $this->zoneRepo->find($zoneId);
        if (!$zone || !$zone->isActive()) {
            return $this->redirectToRoute('game_index');
        }

        if (!$this->gameLogic->isZoneUnlocked($progress, $zone)) {
            $this->addFlash('warning', 'Cette zone n\'est pas encore déverrouillée');
            return $this->redirectToRoute('game_index');
        }

        $progress->setCurrentZoneId($zoneId);
        $this->em->flush();

        $questions = $this->gameLogic->getUnansweredQuestions($progress, $zone);

        return $this->render('game/zone.html.twig', [
            'zone' => $zone,
            'questions' => $questions,
            'player' => $player,
            'hearts' => $progress->getHearts(),
            'points' => $progress->getPoints(),
        ]);
    }

    #[Route('/question/{questionId}', name: 'play_question', methods: ['GET'])]
    public function playQuestion(int $questionId): Response
    {
        $player = $this->playerService->getOrCreatePlayerForUser($this->getUser());
        $progress = $this->gameLogic->getOrCreateProgress($player);

        if ($progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_over');
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

        if ($progress->getHearts() <= 0) {
            return $this->redirectToRoute('game_over');
        }

        $question = $this->questionRepo->find($questionId);
        if (!$question || !$question->isActive()) {
            return $this->redirectToRoute('game_index');
        }

        if (!$this->gameLogic->canPlayerAnswerQuestion($progress, $question)) {
            $this->addFlash('warning', 'Cette question a déjà été répondue');
            return $this->redirectToRoute('game_play_question', ['questionId' => $questionId]);
        }

        $userAnswer = $request->request->get('answer');
        $isCorrect = $question->isCorrectAnswer($userAnswer);
        $result = $this->gameLogic->processQuestionAnswer($progress, $question, $isCorrect);

        if ($result['isGameOver']) {
            return $this->redirectToRoute('game_over');
        }

        $this->addFlash('success', $result['message']);

        $zone = $question->getZone();
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
