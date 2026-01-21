<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\GameService;
use App\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur des résultats et statistiques
 */
#[Route('/results', name: 'results_')]
class ResultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        string $category,
        SessionInterface $session,
        GameService $gameService,
        PlayerService $playerService,
    ): Response {
        // Récupérer le joueur
        $playerEmail = $session->get('player_email');
        $player = $playerService->getPlayer($playerEmail);

        if (!$player) {
            return $this->redirectToRoute('app_home');
        }

        // Récupérer les statistiques
        $stats = $gameService->getPlayerStats($player,$category);

        // Récupérer les résultats par catégorie
        $introResults = $gameService->getCategoryResults($player, 'introduction');
        $foretResults = $gameService->getCategoryResults($player, 'foret');
        $montagneResults = $gameService->getCategoryResults($player, 'montagne');

        return $this->render('results/index.html.twig', [
            'player' => $player,
            'stats' => $stats,
            'introResults' => $introResults,
            'foretResults' => $foretResults,
            'montagneResults' => $montagneResults,
        ]);
    }

    #[Route('/category/{category}', name: 'by_category')]
    public function byCategory(
        string $category,
        SessionInterface $session,
        GameService $gameService,
        PlayerService $playerService,
    ): Response {
        // Récupérer le joueur
        $playerEmail = $session->get('player_email');
        $player = $playerService->getPlayer($playerEmail);

        if (!$player) {
            return $this->redirectToRoute('app_home');
        }

        // Récupérer les résultats pour cette catégorie
        $results = $gameService->getCategoryResults($player, $category);

        return $this->render('results/category.html.twig', [
            'player' => $player,
            'category' => $category,
            'results' => $results,
        ]);
    }

    #[Route('/leaderboard', name: 'leaderboard')]
    public function leaderboard(GameService $gameService): Response
    {
        $leaderboard = $gameService->getLeaderboard(20);

        return $this->render('results/leaderboard.html.twig', [
            'leaderboard' => $leaderboard,
        ]);
    }
}
