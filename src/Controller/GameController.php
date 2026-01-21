<?php

namespace App\Controller;

use App\Form\PlayerNameType;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/game', name: 'game_')]
#[IsGranted('ROLE_USER')]
class GameController extends AbstractController
{
    private array $questions = [
        1 => ['text' => "Comment s'appelle l'ennemi poursuivi ?", 'answer' => "poursuivie"],
        2 => ['text' => "Quel est le mot magique ?", 'answer' => "mojo"],
        3 => ['text' => "Comment s'appelle le compagnon ?", 'answer' => "Navi"],
        4 => ['text' => "Quelle tribu habite la montagne ?", 'answer' => "Gorons"],
        5 => ['text' => "Quelle est la tribu secrète ?", 'answer' => "Sheikah"],
        6 => ['text' => "Quel objet magique est utilisé ?", 'answer' => "Mirroir"],
        7 => ['text' => "Qui est le grand antagoniste ?", 'answer' => "Ganondorf"],
    ];

    public function __construct(private PlayerService $playerService)
    {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $player = $this->playerService->getOrCreatePlayerForUser($user);

        // Calcul des cœurs
        $hearts = (int) floor($player->getScore() / 20);

        return $this->render('game/index.html.twig', [
            'player' => $player,
            'score' => $player->getScore(),
            'hearts' => $hearts,
            'last_step' => $player->getLastStep() ?? 0,
            'has_progress' => $player->getLastStep() !== null,
        ]);
    }

    #[Route('/start', name: 'start', methods: ['POST'])]
    public function startGame(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $player = $this->playerService->getOrCreatePlayerForUser($user);

        // Nouvelle aventure = réinitialisation
        $player->setScore(41);
        $player->setLastStep(0);
        $em->flush();

        // Redirection vers la première étape
        return $this->redirectToRoute('game_step', ['step' => 1]);
    }

    #[Route('/step/{step}', name: 'step', methods: ['GET', 'POST'])]
    public function step(
        int $step,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $player = $this->playerService->getOrCreatePlayerForUser($user);

        // Game over
        if ($player->isGameOver()) {
            return $this->redirectToRoute('game_over');
        }

        // Vérifie que la question existe
        if (!isset($this->questions[$step])) {
            // Si on dépasse le nombre de questions, on termine l'aventure
            return $this->redirectToRoute('game_index');
        }

        $question = $this->questions[$step];
        $resultDisplayed = false;
        $isCorrect = false;
        $scoreChange = 0;
        $userAnswer = null;

        if ($request->isMethod('POST') && $request->request->has('answer')) {
            $userAnswer = trim($request->request->get('answer'));
            $isCorrect = strtolower($userAnswer) === strtolower($question['answer']);
            $scoreChange = $isCorrect ? 3 : -1;

            // Mise à jour du score et de la progression
            $player->setScore(max(0, $player->getScore() + $scoreChange));
            $player->setLastStep($step);
            $em->flush();

            $resultDisplayed = true;

            if ($player->getScore() <= 0) {
                return $this->redirectToRoute('game_over');
            }

            // Passage automatique à la prochaine étape si correct
            if ($isCorrect && isset($this->questions[$step + 1])) {
                return $this->redirectToRoute('game_step', ['step' => $step + 1]);
            }
        }

        $hearts = (int) floor($player->getScore() / 20);

        return $this->render("game/step.html.twig", [
            'player' => $player,
            'questionText' => $question['text'],
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
            'score' => $player->getScore(),
            'hearts' => $hearts,
            'step' => $step,
            'next_step' => $step + 1,
        ]);
    }

    #[Route('/over', name: 'over', methods: ['GET'])]
    public function gameOver(): Response
    {
        $user = $this->getUser();
        $player = $this->playerService->getOrCreatePlayerForUser($user);

        $hearts = (int) floor($player->getScore() / 20);

        return $this->render('game/game_over.html.twig', [
            'player' => $player,
            'score' => $player->getScore(),
            'hearts' => $hearts,
        ]);
    }
}
