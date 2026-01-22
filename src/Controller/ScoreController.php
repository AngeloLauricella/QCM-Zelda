<?php

namespace App\Controller;

use App\Repository\GameProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/scores', name: 'app_score_')]
class ScoreController extends AbstractController
{
    public function __construct(
        private GameProgressRepository $progressRepo,
    ) {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $topScores = $this->progressRepo->findTopScores(10);

        return $this->render('scores/index.html.twig', [
            'topScores' => $topScores,
        ]);
    }
}
