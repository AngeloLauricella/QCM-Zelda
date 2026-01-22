<?php

namespace App\Repository;

use App\Entity\PlayerEventCompletion;
use App\Entity\GameProgress;
use App\Entity\GameEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlayerEventCompletionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerEventCompletion::class);
    }

    public function hasCompletedEvent(GameProgress $progress, GameEvent $event): bool
    {
        return null !== $this->findOneBy([
            'gameProgress' => $progress,
            'gameEvent' => $event,
        ]);
    }

    public function findCompletionsByProgress(GameProgress $progress): array
    {
        return $this->findBy(['gameProgress' => $progress], ['completedAt' => 'DESC']);
    }
}
