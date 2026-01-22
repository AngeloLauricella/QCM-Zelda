<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class ScoreManager
{
    private $session;
    private const INITIAL_SCORE = 0; 
    private const MIN_SCORE = -1;
    private const HEART_VALUE = 10;

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
    }

    /**
     * Initialise le score à la valeur de départ
     */
    public function initializeScore(): void
    {
        // Réinitialise la session et le score
        $this->session->invalidate();
        $this->session->start();
        $this->session->set('score', self::INITIAL_SCORE);
    }

    /**
     * Retourne le score actuel
     */
    public function getScore(): int
    {
        return $this->session->get('score', self::INITIAL_SCORE);
    }

    /**
     * Définit le score
     */
    public function setScore(int $score): void
    {
        $score = max(self::MIN_SCORE, $score);
        $this->session->set('score', $score);
    }

    /**
     * Ajoute des points au score
     */
    public function addPoints(int $points): void
    {
        $currentScore = $this->getScore();
        $this->setScore($currentScore + $points);
    }

    /**
     * Enlève des points au score
     */
    public function removePoints(int $points): void
    {
        $currentScore = $this->getScore();
        $this->setScore($currentScore - $points);
    }

    /**
     * Vérifie si le joueur a perdu (score <= 0)
     */
    public function isGameOver(): bool
    {
        return $this->getScore() <= -1;
    }

    /**
     * Retourne le nombre de cœurs à afficher
     */
    public function getHeartsCount(): int
    {
        return intval(floor($this->getScore() / self::HEART_VALUE));
    }

    /**
     * Retourne le nombre de points restants pour un cœur partiel
     */
    public function getRemainingPoints(): int
    {
        return $this->getScore() % self::HEART_VALUE;
    }

    /**
     * Retourne le score et le nombre de cœurs
     */
    public function getScoreDetails(): array
    {
        return [
            'score' => $this->getScore(),
            'hearts' => $this->getHeartsCount(),
            'remaining_points' => $this->getRemainingPoints(),
            'is_game_over' => $this->isGameOver(),
        ];
    }
}
