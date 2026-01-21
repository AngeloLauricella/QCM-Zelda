<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuestionManager
{
    private SessionInterface $session;
    private ScoreManager $scoreManager;

    public function __construct(SessionInterface $session, ScoreManager $scoreManager)
    {
        $this->session = $session;
        $this->scoreManager = $scoreManager;
    }

    /**
     * Traite la réponse à une question et met à jour le score
     */
    public function processAnswer(string $questionId, string $userAnswer, string $correctAnswer, int $rightPoints = 3, int $wrongPoints = -1): array
    {
        $isCorrect = $userAnswer === $correctAnswer;

        if ($isCorrect) {
            $this->scoreManager->addPoints($rightPoints);
            $result = 'correct';
        } else {
            $this->scoreManager->addPoints($wrongPoints);
            $result = 'incorrect';
        }

        return [
            'is_correct' => $isCorrect,
            'result' => $result,
            'current_score' => $this->scoreManager->getScore(),
            'points_change' => $isCorrect ? $rightPoints : $wrongPoints,
        ];
    }

    /**
     * Enregistre les réponses du joueur
     */
    public function recordAnswer(string $questionId, string $answer): void
    {
        $answers = $this->session->get('answers', []);
        $answers[$questionId] = $answer;
        $this->session->set('answers', $answers);
    }

    /**
     * Retourne toutes les réponses du joueur
     */
    public function getAnswers(): array
    {
        return $this->session->get('answers', []);
    }

    /**
     * Retourne une réponse spécifique
     */
    public function getAnswer(string $questionId): ?string
    {
        $answers = $this->getAnswers();
        return $answers[$questionId] ?? null;
    }
}
