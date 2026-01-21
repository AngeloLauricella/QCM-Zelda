<?php

namespace App\Controller;

use App\Service\ScoreManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'game_index')]
    public function index(ScoreManager $scoreManager): Response
    {
        // Réinitialise le score au chargement de la page d'accueil
        $scoreManager->initializeScore();

        return $this->render('game/index.html.twig', [
            'score' => $scoreManager->getScore(),
        ]);
    }

    #[Route('/galerie', name: 'game_gallery')]
    public function gallery(): Response
    {
        return $this->render('game/gallery.html.twig', []);
    }

    #[Route('/dead', name: 'game_over')]
    public function gameOver(ScoreManager $scoreManager): Response
    {
        return $this->render('game/game_over.html.twig', [
            'score' => $scoreManager->getScore(),
        ]);
    }
}
