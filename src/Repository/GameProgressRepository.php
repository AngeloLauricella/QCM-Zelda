<?php

namespace App\Repository;

use App\Entity\GameProgress;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameProgress::class);
    }

    public function findActiveByPlayer(Player $player): ?GameProgress
    {
        return $this->createQueryBuilder('gp')
            ->andWhere('gp.player = :player')
            ->andWhere('gp.isGameOver = false')
            ->setParameter('player', $player)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastCompletedByPlayer(Player $player): ?GameProgress
    {
        return $this->createQueryBuilder('gp')
            ->andWhere('gp.player = :player')
            ->andWhere('gp.isGameOver = true')
            ->setParameter('player', $player)
            ->orderBy('gp.endedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTopScores(int $limit = 10): array
    {
        return $this->createQueryBuilder('gp')
            ->select('gp, p')
            ->join('gp.player', 'p')
            ->andWhere('gp.isGameOver = true')
            ->orderBy('gp.points', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
