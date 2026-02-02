<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/scores', name: 'app_score_')]
class ScoreController extends AbstractController
{
    public function __construct(
        private ScoreRepository $scoreRepo,
    ) {
    }

    /**
     * Page des scores personnels de l'utilisateur connecté
     */
    #[Route('/me', name: 'my_scores', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function myScores(): Response
    {
        $user = $this->getUser();
        $myScores = $this->scoreRepo->findByUserOrdered($user);
        $bestScore = $this->scoreRepo->getBestScoreByUser($user);
        $averageScore = $this->scoreRepo->getAverageScoreByUser($user);

        return $this->render('scores/my_scores.html.twig', [
            'myScores' => $myScores,
            'bestScore' => $bestScore,
            'averageScore' => $averageScore,
            'totalScores' => count($myScores),
        ]);
    }

    /**
     * Page du leaderboard global - top scores de tous les joueurs
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function leaderboard(): Response
    {
        $topScores = $this->scoreRepo->findTopScores(50);
        $currentUser = $this->getUser();

        return $this->render('scores/index.html.twig', [
            'topScores' => $topScores,
            'currentUser' => $currentUser,
        ]);
    }
}
