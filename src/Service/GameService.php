<?php

namespace App\Service;

use App\Entity\GameProgress;
use App\Entity\GameResult;
use App\Entity\Player;
use App\Entity\PlayerEventCompletion;
use App\Entity\Question;
use App\Entity\ZoneProgress;
use App\Repository\ZoneRepository;
use App\Repository\ZoneProgressRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion du jeu
 * Centralise la logique métier du jeu
 */
class GameService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ZoneRepository $zoneRepository,
        private ZoneProgressRepository $zoneProgressRepository,
    ) {
    }

    /**
     * Traite une réponse d'un joueur à une question
     */
    public function processAnswer(Player $player, Question $question, string $answer): GameResult
    {
        $result = new GameResult($player, $question);
        $result->setUserAnswer($answer);

        $pointsEarned = $result->isCorrect()
            ? $question->getPointsValue()
            : 0;

        $result->setPointsEarned($pointsEarned);

        // 🔥 Récupérer la progression
        $progress = $this->entityManager
            ->getRepository(GameProgress::class)
            ->findOneBy(['player' => $player]);

        if ($progress) {
            $progress->setPoints(
                max(0, $progress->getPoints() + $pointsEarned)
            );
        }

        $result->setScoreAfter($progress?->getPoints() ?? 0);

        $this->entityManager->persist($result);

        if ($progress) {
            $this->entityManager->persist($progress);
        }

        $this->entityManager->flush();

        return $result;
    }
    public function getAvailableZones(?Player $player): array
    {
        if (!$player) {
            return [];
        }

        $zones = $this->zoneRepository->findBy(['isActive' => true]);
        $result = [];

        foreach ($zones as $zone) {
            $progress = $this->zoneProgressRepository->findOneBy([
                'player' => $player,
                'zone' => $zone
            ]);

            $result[] = [
                'id' => $zone->getId(),
                'name' => $zone->getName(),
                'description' => $zone->getDescription(),
                'icon' => '🌿', // tu peux mettre un champ spécifique si tu veux
                'isCompleted' => $progress?->isCompleted() ?? false,
                'isCurrent' => $progress?->isUnlocked() ?? false,
                'unlocked' => $progress?->isUnlocked() ?? false,
            ];
        }

        return $result;
    }
    /**
     * Réinitialise la partie d'un joueur
     */
    public function resetGame(Player $player): void
    {
        $progress = $this->entityManager
            ->getRepository(GameProgress::class)
            ->findOneBy(['player' => $player]);

        if (!$progress) {
            return;
        }

        $progress->reset();

        // 2️⃣ Supprimer toutes les progressions de zone
        $zoneProgresses = $this->entityManager
            ->getRepository(ZoneProgress::class)
            ->findBy(['player' => $player]);
        foreach ($zoneProgresses as $zoneProgress) {
            $this->entityManager->remove($zoneProgress);
        }

        // 3️⃣ Supprimer les completions de questions
        $completions = $this->entityManager
            ->getRepository(PlayerEventCompletion::class)
            ->findBy(['gameProgress' => $progress]);

        foreach ($completions as $completion) {
            $this->entityManager->remove($completion);
        }

        $this->entityManager->flush();
    }
    /**
     * Obtient les statistiques d'un joueur
     */
    public function getPlayerStats(Player $player, string $category): array
    {
        $results = $this->entityManager
            ->getRepository(GameResult::class)
            ->findByPlayer($player, $category);

        $totalAnswers = count($results);
        $correctAnswers = count(array_filter($results, fn($r) => $r->isCorrect()));
        $wrongAnswers = $totalAnswers - $correctAnswers;

        $progress = $this->entityManager
            ->getRepository(GameProgress::class)
            ->findOneBy(['player' => $player]);

        return [
            'totalAnswers' => $totalAnswers,
            'correctAnswers' => $correctAnswers,
            'wrongAnswers' => $wrongAnswers,
            'percentage' => $totalAnswers > 0
                ? round(($correctAnswers / $totalAnswers) * 100, 2)
                : 0,
            'points' => $progress?->getPoints() ?? 0,
            'hearts' => $progress?->getHearts() ?? 0,
            'isGameOver' => $progress?->isGameOver() ?? false,
        ];
    }

    /**
     * Obtient le classement des meilleurs scores
     */
    public function getLeaderboard(int $limit = 10): array
    {
        return $this->entityManager
            ->getRepository(Player::class)
            ->findTopScores($limit);
    }

    /**
     * Récupère les résultats d'une catégorie pour un joueur
     */
    public function getCategoryResults(Player $player, string $category): array
    {
        return $this->entityManager
            ->getRepository(GameResult::class)
            ->findByCategoryAndPlayer($player, $category);
    }
}
