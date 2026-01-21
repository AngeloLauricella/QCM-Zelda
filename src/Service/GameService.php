<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Question;
use App\Entity\GameResult;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion du jeu
 * Centralise la logique métier du jeu
 */
class GameService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Traite une réponse d'un joueur à une question
     */
    public function processAnswer(Player $player, Question $question, string $answer): GameResult
    {
        $result = new GameResult($player, $question);
        $result->setUserAnswer($answer);

        $pointsEarned = $result->isCorrect() ? $question->getPointsValue() : 0;
        $result->setPointsEarned($pointsEarned);

        // Mise à jour sécurisée du score
        $player->setScore(max(0, $player->getScore() + $pointsEarned));
        $result->setScoreAfter($player->getScore());

        $this->entityManager->persist($result);
        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $result;
    }
    /**
     * Obtient la prochaine question pour un joueur dans une catégorie
     */
    public function getNextQuestion(Player $player, string $category): ?Question
    {
        $questionRepository = $this->entityManager->getRepository(Question::class);
        $gameResultRepository = $this->entityManager->getRepository(GameResult::class);

        $questions = $questionRepository->findByCategory($category);
        if (empty($questions))
            return null;

        $answeredQuestions = $gameResultRepository->findByCategoryAndPlayer($player, $category);
        $answeredIds = array_map(fn(GameResult $result) => $result->getQuestion()->getId(), $answeredQuestions);

        $remainingQuestions = array_filter($questions, fn($q) => !in_array($q->getId(), $answeredIds));

        return !empty($remainingQuestions)
            ? $remainingQuestions[array_rand($remainingQuestions)]
            : null; // ou aléatoire si souhaité
    }


    /**
     * Obtient les statistiques d'un joueur
     */
    public function getPlayerStats(Player $player, string $category): array
    {
        $gameResultRepository = $this->entityManager->getRepository(GameResult::class);
        $results = $gameResultRepository->findByPlayer($player, $category);

        $totalAnswers = count($results);
        $correctAnswers = count(array_filter($results, fn($r) => $r->isCorrect()));
        $wrongAnswers = $totalAnswers - $correctAnswers;

        return [
            'totalAnswers' => $totalAnswers,
            'correctAnswers' => $correctAnswers,
            'wrongAnswers' => $wrongAnswers,
            'percentage' => $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 2) : 0,
            'score' => $player->getScore(),
            'hearts' => $player->getHearts(),
            'isGameOver' => $player->isGameOver(),
        ];
    }


    /**
     * Réinitialise la partie d'un joueur
     */
    public function resetGame(Player $player): Player
    {
        $player->setScore(0); // reset à 0
        $player->setHearts(3);

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player;
    }

    /**
     * Obtient le classement des meilleurs scores
     */
    public function getLeaderboard(int $limit = 10): array
    {
        $playerRepository = $this->entityManager->getRepository(Player::class);
        return $playerRepository->findTopScores($limit);
    }

    /**
     * Récupère les résultats d'une catégorie pour un joueur
     */
    public function getCategoryResults(Player $player, string $category): array
    {
        $gameResultRepository = $this->entityManager->getRepository(GameResult::class);
        return $gameResultRepository->findByCategoryAndPlayer($player, $category);
    }
}
