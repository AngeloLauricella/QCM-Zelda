<?php

namespace App\Repository;

use App\Entity\ShopItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ShopItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopItem::class);
    }

    public function findAvailable(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.isAvailable = :available')
            ->andWhere('(s.stock = -1 OR s.stock > 0)')
            ->setParameter('available', true)
            ->orderBy('s.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
