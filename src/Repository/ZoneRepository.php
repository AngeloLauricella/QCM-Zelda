<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    public function findActiveZones(): array
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('z.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findUnlockedZones(int $playerPoints): array
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.isActive = :active')
            ->andWhere('z.minPointsToUnlock <= :points')
            ->setParameter('active', true)
            ->setParameter('points', $playerPoints)
            ->orderBy('z.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
