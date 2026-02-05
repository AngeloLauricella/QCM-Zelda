<?php

namespace App\Repository;

use App\Entity\ZoneProgress;
use App\Entity\Player;
use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ZoneProgress>
 */
class ZoneProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneProgress::class);
    }

    public function findByPlayer(Player $player): array
    {
        return $this->findBy(['player' => $player], ['zone' => 'ASC']);
    }

    public function findByPlayerAndZone(Player $player, Zone $zone): ?ZoneProgress
    {
        return $this->findOneBy(['player' => $player, 'zone' => $zone]);
    }

    public function findCompletedZonesByPlayer(Player $player): array
    {
        return $this->findBy(
            ['player' => $player, 'status' => ZoneProgress::STATUS_COMPLETED],
            ['zone' => 'ASC']
        );
    }

    public function findUnlockedZonesByPlayer(Player $player): array
    {
        return $this->findBy(
            ['player' => $player, 'status' => ZoneProgress::STATUS_UNLOCKED],
            ['zone' => 'ASC']
        );
    }

    public function findLockedZonesByPlayer(Player $player): array
    {
        return $this->findBy(
            ['player' => $player, 'status' => ZoneProgress::STATUS_LOCKED],
            ['zone' => 'ASC']
        );
    }

    public function findCurrentlyPlayableZone(Player $player): ?ZoneProgress
    {
        // La zone actuellement jouable est la première zone débloquée (unlocked) ET non complétée
        $result = $this->findBy(
            ['player' => $player, 'status' => ZoneProgress::STATUS_UNLOCKED],
            ['zone' => 'ASC'],
            1
        );
        return $result[0] ?? null;
    }

    public function countCompletedZonesByPlayer(Player $player): int
    {
        return $this->count(['player' => $player, 'status' => ZoneProgress::STATUS_COMPLETED]);
    }

    public function countUnlockedZonesByPlayer(Player $player): int
    {
        return $this->count(['player' => $player, 'status' => ZoneProgress::STATUS_UNLOCKED]);
    }

    public function findByPlayerOrderedByZone(Player $player): array
    {
        return $this->findBy(['player' => $player], ['zone' => 'ASC']);
    }
}
