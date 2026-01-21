<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\GameService;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrôleur des questions
 */
#[Route('/question', name: 'question_')]
class QuestionController extends AbstractController
{
    /**
     * Affiche la prochaine question d'une catégorie
     */
    #[Route('/{category}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(
        string $category,
        GameService $gameService,
        PlayerService $playerService,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $player = $playerService->getPlayer($user->getUserIdentifier());

        if (!$player) {
            return $this->redirectToRoute('app_home');
        }

        if ($player->isGameOver()) {
            return $this->redirectToRoute('question_gameover');
        }

        $question = $gameService->getNextQuestion($player, $category);

        if (!$question) {
            return $this->redirectToRoute('app_home');
        }

        $stats = $gameService->getPlayerStats($player, $category);

        return $this->render('question/show.html.twig', [
            'player' => $player,
            'question' => $question,
            'category' => $category,
            'stats' => $stats,
        ]);
    }

    /**
     * Traite la réponse du joueur
     */
    #[Route('/{category}/answer', name: 'answer', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function answer(
        string $category,
        Request $request,
        GameService $gameService,
        PlayerService $playerService,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $player = $playerService->getPlayer($user->getUserIdentifier());

        if (!$player) {
            return $this->redirectToRoute('app_home');
        }

        $questionId = $request->request->get('question_id');
        $answer = $request->request->get('answer');

        $questionRepository = $entityManager->getRepository('App\Entity\Question');
        $question = $questionRepository->find($questionId);

        if (!$question) {
            return $this->redirectToRoute('question_show', ['category' => $category]);
        }

        $result = $gameService->processAnswer($player, $question, $answer);

        // Rafraîchir le joueur depuis la DB
        $entityManager->refresh($player);

        if ($player->isGameOver()) {
            return $this->redirectToRoute('question_gameover');
        }

        return $this->render('question/result.html.twig', [
            'player' => $player,
            'question' => $question,
            'result' => $result,
            'category' => $category,
        ]);
    }

    /**
     * Affiche l'écran Game Over
     */
    #[Route('/gameover', name: 'gameover')]
    #[IsGranted('ROLE_USER')]
    public function gameOver(
        string $category,
        GameService $gameService,
        PlayerService $playerService
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $player = $playerService->getPlayer($user->getUserIdentifier());

        if (!$player) {
            return $this->redirectToRoute('app_home');
        }

        $stats = $gameService->getPlayerStats($player, $category);

        return $this->render('question/gameover.html.twig', [
            'player' => $player,
            'stats' => $stats,
        ]);
    }
}
