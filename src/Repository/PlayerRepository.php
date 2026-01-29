<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * Récupère un joueur par email
     */
    public function findByEmail(string $email): ?Player
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Récupère tous les joueurs actifs
     */
    public function findActivePlayers(): array
    {
        return $this->findBy(['isActive' => true], ['createdAt' => 'DESC']);
    }

    /**
     * Récupère les meilleurs scores
     */
    public function findTopScores(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.score', 's')
            ->addSelect('s')
            ->andWhere('p.isActive = true')
            ->orderBy('s.value', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    /**
     * Récupère les joueurs créés aujourd'hui
     */
    public function findTodayPlayers(): array
    {
        $today = new \DateTimeImmutable('today');
        $tomorrow = $today->modify('+1 day');

        return $this->createQueryBuilder('p')
            ->andWhere('p.createdAt >= :today')
            ->andWhere('p.createdAt < :tomorrow')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
