<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\GalleryRepository;
use App\Repository\ScoreRepository;

/**
 * Service pour gérer les statistiques utilisateur
 */
class UserStatisticsService
{
    public function __construct(
        private ScoreRepository $scoreRepository,
        private GalleryRepository $galleryRepository,
    ) {
    }

    /**
     * Obtenir toutes les statistiques d'un utilisateur
     */
    public function getUserStatistics(User $user): array
    {
        // Passer directement l'entité User aux repository
        $scores = $this->scoreRepository->findByUserOrdered($user);
        $galleries = $this->galleryRepository->findByUserOrdered($user);

        return [
            'user' => $user,
            'totalScores' => count($scores),
            'bestScore' => $this->scoreRepository->getBestScore($user),
            'averageScore' => $this->scoreRepository->getAverageScore($user) ?? 0,
            'totalGalleries' => count($galleries),
            'recentScores' => array_slice($scores, 0, 5),
            'recentGalleries' => array_slice($galleries, 0, 5),
            'memberSince' => $user->getCreatedAt(),
        ];
    }

    /**
     * Vérifier si l'utilisateur a un bon score
     */
    public function isHighAchiever(User $user, int $threshold = 80): bool
    {
        $bestScore = $this->scoreRepository->getBestScore($user);
        return $bestScore !== null && $bestScore >= $threshold;
    }

    /**
     * Obtenir le classement d'un utilisateur parmi tous les utilisateurs
     * (Note: À implémenter avec une requête appropriée)
     */
    public function getUserRank(User $user): array
    {
        $bestScore = $this->scoreRepository->getBestScore($user);
        $averageScore = $this->scoreRepository->getAverageScore($user) ?? 0;

        return [
            'bestScore' => $bestScore,
            'averageScore' => round($averageScore, 2),
            'scoreCount' => count($this->scoreRepository->findByUserOrdered($user)),
        ];
    }

    /**
     * Obtenir un résumé rapide de l'utilisateur
     */
    public function getQuickSummary(User $user): array
    {
        return [
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'bestScore' => $this->scoreRepository->getBestScore($user),
            'totalScores' => count($this->scoreRepository->findByUserOrdered($user)),
            'totalGalleries' => count($this->galleryRepository->findByUserOrdered($user)),
        ];
    }
}
