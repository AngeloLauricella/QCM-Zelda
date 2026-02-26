<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\GameService;
use App\Service\PlayerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
    #[Route('', name: 'home')]
    public function index(
        SessionInterface $session,
        GameService $gameService,
        PlayerService $playerService,
    ): Response {

        $user = $this->getUser();
        $category = 'introduction';

        // 🔐 Gestion utilisateur / invité
        if ($user instanceof User) {
            $playerEmail = $user->getEmail();
            $username = $user->getUsername();
        } else {
            $playerEmail = $session->get('player_email');
            $username = $session->get('player_name');
        }

        // 🎮 Récupération du Player
        $player = $playerEmail
            ? $playerService->getPlayer($playerEmail)
            : null;

        $session->set('player_id', $player?->getId());

        // 📊 Stats
        $stats = $player
            ? $gameService->getPlayerStats($player, $category)
            : null;

        // 🏆 Leaderboard
        $leaderboard = $gameService->getLeaderboard(5);

        // 🌍 Zones disponibles (SAFE)
        $zones = $player
            ? $gameService->getAvailableZones($player)
            : [];

        return $this->render('home/index.html.twig', [
            'player' => $player,
            'stats' => $stats,
            'leaderboard' => $leaderboard,
            'username' => $username ?? 'Invité',
            'user' => $user,
            'zones' => $zones,
        ]);
    }

    #[Route('/gallery', name: 'gallery')]
    public function gallery(
        SessionInterface $session,
        PlayerService $playerService
    ): Response {

        $user = $this->getUser();

        $playerEmail = $user instanceof User
            ? $user->getEmail()
            : $session->get('player_email');

        $player = $playerService->getPlayer($playerEmail);

        if (!$player) {
            return $this->redirectToRoute('app_home');
        }

        $username = $user instanceof User
            ? $user->getUsername()
            : $session->get('player_name');

        return $this->render('home/gallery.html.twig', [
            'player' => $player,
            'username' => $username,
            'user' => $user,
        ]);
    }

    #[Route('/reset', name: 'reset')]
    public function reset(
        SessionInterface $session,
        GameService $gameService,
        PlayerService $playerService,
    ): Response {

        $user = $this->getUser();

        $playerEmail = $user instanceof User
            ? $user->getEmail()
            : $session->get('player_email');

        $player = $playerService->getPlayer($playerEmail);

        if ($player) {
            $gameService->resetGame($player);
        }

        return $this->redirectToRoute('app_home');
    }
}