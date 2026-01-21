<?php

namespace App\Controller;

use App\Service\ScoreManager;
use App\Service\QuestionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/introduction', name: 'introduction_')]
class IntroductionController extends AbstractController
{
    #[Route('/1', name: 'intro1')]
    public function intro1(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'poursuivie';

            $result = $questionManager->processAnswer('intro1', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('intro1', $userAnswer);

            // Vérifier le score après la réponse
            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('introduction/intro1.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }

    #[Route('/2', name: 'intro2')]
    public function intro2(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'mojo';

            $result = $questionManager->processAnswer('intro2', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('intro2', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('introduction/intro2.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }

    #[Route('/3', name: 'intro3')]
    public function intro3(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'Navi';

            $result = $questionManager->processAnswer('intro3', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('intro3', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('introduction/intro3.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }

    #[Route('/4', name: 'intro4')]
    public function intro4(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'Gorons';

            $result = $questionManager->processAnswer('intro4', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('intro4', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('introduction/intro4.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }

    #[Route('/5', name: 'intro5')]
    public function intro5(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'Sheikah';

            $result = $questionManager->processAnswer('intro5', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('intro5', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('introduction/intro5.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }

    #[Route('/6', name: 'intro6')]
    public function intro6(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'Mirroir';

            $result = $questionManager->processAnswer('intro6', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('intro6', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('introduction/intro6.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }

    #[Route('/7', name: 'intro7')]
    public function intro7(Request $request, ScoreManager $scoreManager, QuestionManager $questionManager): Response
    {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            $correctAnswer = 'Ganondorf';

            $result = $questionManager->processAnswer('intro7', $userAnswer, $correctAnswer, 3, -1);
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer('intro7', $userAnswer);

            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render('introduction/intro7.html.twig', [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }
}
