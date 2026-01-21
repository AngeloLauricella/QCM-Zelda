<?php

namespace App\Controller;

use App\Service\ScoreManager;
use App\Service\QuestionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/montagne', name: 'montagne_')]
class MontagneController extends AbstractController
{
    #[Route('', name: 'montagne')]
    public function montagne(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'Temple';

            $result = $questionManager->processAnswer('montagne', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('montagne', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('montagne/montagne.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }
}
