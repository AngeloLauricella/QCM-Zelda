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

        // Si utilisateur connecté, utiliser son email pour récupérer ou créer un Player
        if ($user instanceof User) {
            $playerEmail = $user->getEmail(); // email du User Symfony
        } else {
            // Sinon, joueur de session
            $playerEmail = $session->get('player_email');

            if (!$playerEmail) {
                $playerEmail = 'player_' . uniqid() . '@game.local';
                $session->set('player_email', $playerEmail);
            }
        }

        // Récupérer ou créer le Player
        $player = $playerService->getOrCreateSessionPlayer($playerEmail);
        $session->set('player_id', $player->getId());

        // Statistiques et classement
        $stats = $gameService->getPlayerStats($player);
        $leaderboard = $gameService->getLeaderboard(5);

        return $this->render('home/index.html.twig', [
            'player' => $player,
            'stats' => $stats,
            'leaderboard' => $leaderboard,
            'user' => $user, // utile si tu veux afficher des infos spécifiques à Symfony User
        ]);
    }

    #[Route('/gallery', name: 'gallery')]
    public function gallery(SessionInterface $session, PlayerService $playerService): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            $playerEmail = $user->getEmail();
        } else {
            $playerEmail = $session->get('player_email');
        }

        $player = $playerService->getPlayer($playerEmail);

        if (!$player) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/gallery.html.twig', [
            'player' => $player,
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

        if ($user instanceof User) {
            $playerEmail = $user->getEmail();
        } else {
            $playerEmail = $session->get('player_email');
        }

        $player = $playerService->getPlayer($playerEmail);

        if ($player) {
            $gameService->resetGame($player);
        }

        return $this->redirectToRoute('app_home');
    }
}
