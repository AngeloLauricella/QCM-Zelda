<?php

namespace App\Controller;

use App\Service\ScoreManager;
use App\Service\QuestionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur de base pour les questions du jeu
 * Refactorisation pour éviter la duplication de code
 */
abstract class BaseQuestionController extends AbstractController
{
    /**
     * Traite une question standard du jeu
     */
    protected function handleQuestion(
        Request $request,
        ScoreManager $scoreManager,
        QuestionManager $questionManager,
        string $questionId,
        string $correctAnswer,
        string $templateName,
        int $rightPoints = 3,
        int $wrongPoints = -1
    ): Response {
        $score = $scoreManager->getScore();
        $resultDisplayed = false;
        $userAnswer = null;
        $isCorrect = false;
        $scoreChange = 0;
        $newScore = $score;

        if ($request->isMethod('POST') && $request->request->has('question')) {
            $userAnswer = $request->request->get('question');
            
            $result = $questionManager->processAnswer(
                $questionId,
                $userAnswer,
                $correctAnswer,
                $rightPoints,
                $wrongPoints
            );
            
            $isCorrect = $result['is_correct'];
            $scoreChange = $result['points_change'];
            $newScore = $result['current_score'];
            $resultDisplayed = true;

            $questionManager->recordAnswer($questionId, $userAnswer);

            // Vérifier si le jeu est terminé
            if ($scoreManager->isGameOver()) {
                return $this->redirectToRoute('game_over');
            }
        }

        return $this->render($templateName, [
            'score' => $newScore,
            'hearts' => $scoreManager->getHeartsCount(),
            'result_displayed' => $resultDisplayed,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'score_change' => $scoreChange,
        ]);
    }
}
