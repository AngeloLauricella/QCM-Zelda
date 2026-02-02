<?php

namespace App\Repository;

use App\Entity\Score;
use App\Entity\User;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    /**
     * Find all scores ordered by value descending (leaderboard)
     *
     * @return Score[]
     */
    public function findTopScores(int $limit = 10): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.player', 'p')
            ->addSelect('p')
            ->orderBy('s.value', 'DESC')
            ->addOrderBy('s.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all scores for a player ordered by creation date descending
     *
     * @return Score[]
     */
    public function findByPlayerOrdered(Player $player): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.player = :player')
            ->setParameter('player', $player)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all scores for a user (via player relationship)
     *
     * @return Score[]
     */
    public function findByUserOrdered(User $user): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.player', 'p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get the best score for a player
     */
    public function getBestScore(Player $player): ?int
    {
        return $this->createQueryBuilder('s')
            ->select('MAX(s.value)')
            ->andWhere('s.player = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get the best score for a user (via player)
     */
    public function getBestScoreByUser(User $user): ?int
    {
        return $this->createQueryBuilder('s')
            ->select('MAX(s.value)')
            ->leftJoin('s.player', 'p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get average score for a player
     */
    public function getAverageScore(Player $player): ?float
    {
        return $this->createQueryBuilder('s')
            ->select('AVG(s.value)')
            ->andWhere('s.player = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get average score for a user (via player)
     */
    public function getAverageScoreByUser(User $user): ?float
    {
        return $this->createQueryBuilder('s')
            ->select('AVG(s.value)')
            ->leftJoin('s.player', 'p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
