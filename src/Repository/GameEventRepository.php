<?php

namespace App\Repository;

use App\Entity\GameEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameEvent::class);
    }

    public function findActiveByZone($zone): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.zone = :zone')
            ->andWhere('e.isActive = :active')
            ->setParameter('zone', $zone)
            ->setParameter('active', true)
            ->orderBy('e.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
