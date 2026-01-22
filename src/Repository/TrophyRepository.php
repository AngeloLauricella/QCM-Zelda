<?php

namespace App\Repository;

use App\Entity\Trophy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrophyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trophy::class);
    }

    public function findVisibleTrophies(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isVisible = :visible')
            ->setParameter('visible', true)
            ->orderBy('t.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type = :type')
            ->andWhere('t.isVisible = :visible')
            ->setParameter('type', $type)
            ->setParameter('visible', true)
            ->orderBy('t.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
