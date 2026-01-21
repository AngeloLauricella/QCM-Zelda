<?php

namespace App\Controller;

use App\Service\ScoreManager;
use App\Service\QuestionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foret', name: 'foret_')]
class ForetController extends AbstractController
{
    #[Route('/1', name: 'foret1')]
    public function foret1(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'Saria';

            $result = $questionManager->processAnswer('foret1', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('foret1', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('foret/foret1.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }

    #[Route('/2', name: 'foret2')]
    public function foret2(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        return $this->render('foret/foret2.html.twig', [
            'score' => $scoreManager->getScore(),
            'hearts' => $scoreManager->getHeartsCount(),
        ]);
    }

    #[Route('/3', name: 'foret3')]
    public function foret3(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        return $this->render('foret/foret3.html.twig', [
            'score' => $scoreManager->getScore(),
            'hearts' => $scoreManager->getHeartsCount(),
        ]);
    }

    #[Route('/4', name: 'foret4')]
    public function foret4(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        return $this->render('foret/foret4.html.twig', [
            'score' => $scoreManager->getScore(),
            'hearts' => $scoreManager->getHeartsCount(),
        ]);
    }

    #[Route('/5', name: 'foret5')]
    public function foret5(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        return $this->render('foret/foret5.html.twig', [
            'score' => $scoreManager->getScore(),
            'hearts' => $scoreManager->getHeartsCount(),
        ]);
    }

    #[Route('/6', name: 'foret6')]
    public function foret6(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        return $this->render('foret/foret6.html.twig', [
            'score' => $scoreManager->getScore(),
            'hearts' => $scoreManager->getHeartsCount(),
        ]);
    }

    #[Route('/7', name: 'foret7')]
    public function foret7(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        return $this->render('foret/foret7.html.twig', [
            'score' => $scoreManager->getScore(),
            'hearts' => $scoreManager->getHeartsCount(),
        ]);
    }

    #[Route('/liane', name: 'foret_liane')]
    public function liane(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        return $this->render('foret/liane.html.twig', [
            'score' => $scoreManager->getScore(),
            'hearts' => $scoreManager->getHeartsCount(),
        ]);
    }
}
