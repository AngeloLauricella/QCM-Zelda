<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Zone;
use App\Entity\ZoneProgress;
use App\Repository\ZoneProgressRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;

class ZoneProgressionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ZoneProgressRepository $zoneProgressRepo,
        private ZoneRepository $zoneRepo,
    ) {
    }

    /**
     * Initialiser la progression des zones pour un joueur
     * (appelé au démarrage d'une nouvelle aventure)
     */
    public function initializeZonesForPlayer(Player $player): void
    {
        // Récupérer toutes les zones actives
        $allZones = $this->zoneRepo->findBy(['isActive' => true], ['displayOrder' => 'ASC']);

        foreach ($allZones as $index => $zone) {
            // La première zone est débloquée, les autres sont verrouillées
            $status = $index === 0 ? ZoneProgress::STATUS_UNLOCKED : ZoneProgress::STATUS_LOCKED;

            // Vérifier si la progression existe déjà
            $existing = $this->zoneProgressRepo->findByPlayerAndZone($player, $zone);
            if (!$existing) {
                $progress = new ZoneProgress($player, $zone, $status);
                $this->em->persist($progress);
            }
        }

        $this->em->flush();
    }

    /**
     * Obtenir ou créer la progression pour une zone
     * Crée en tant que UNLOCKED si c'est la première zone
     */
    public function getOrCreateZoneProgress(Player $player, Zone $zone): ZoneProgress
    {
        $progress = $this->zoneProgressRepo->findByPlayerAndZone($player, $zone);

        if (!$progress) {
            // Vérifier si c'est la première zone
            $allZones = $this->zoneRepo->findBy(['isActive' => true], ['displayOrder' => 'ASC']);
            $isFirstZone = count($allZones) > 0 && $allZones[0]->getId() === $zone->getId();
            
            $status = $isFirstZone ? ZoneProgress::STATUS_UNLOCKED : ZoneProgress::STATUS_LOCKED;
            $progress = new ZoneProgress($player, $zone, $status);
            $this->em->persist($progress);
            $this->em->flush();
        }

        return $progress;
    }

    /**
     * Débloquer une zone
     */
    public function unlockZone(Player $player, Zone $zone): ZoneProgress
    {
        $progress = $this->getOrCreateZoneProgress($player, $zone);
        if ($progress->isLocked()) {
            $progress->unlock();
            $this->em->flush();
        }
        return $progress;
    }

    /**
     * Marquer une zone comme complétée et débloquer la suivante
     */
    public function completeZone(Player $player, Zone $zone): ZoneProgress
    {
        $progress = $this->getOrCreateZoneProgress($player, $zone);

        if (!$progress->isCompleted()) {
            $progress->complete();
            $this->em->flush();

            // Débloquer la zone suivante
            $this->unlockNextZone($player, $zone);
        }

        return $progress;
    }

    /**
     * Débloquer la zone suivante
     */
    private function unlockNextZone(Player $player, Zone $currentZone): void
    {
        $allZones = $this->zoneRepo->findBy(['isActive' => true], ['displayOrder' => 'ASC']);

        $currentIndex = array_search($currentZone, $allZones);
        if ($currentIndex !== false && isset($allZones[$currentIndex + 1])) {
            $nextZone = $allZones[$currentIndex + 1];
            $this->unlockZone($player, $nextZone);
        }
    }

    /**
     * Obtenir la zone en cours de jeu
     */
    public function getCurrentPlayableZone(Player $player): ?Zone
    {
        $progress = $this->zoneProgressRepo->findCurrentlyPlayableZone($player);
        return $progress?->getZone();
    }

    /**
     * Obtenir toutes les zones débloquées (complétées + en cours)
     */
    public function getUnlockedZones(Player $player): array
    {
        $completed = $this->zoneProgressRepo->findCompletedZonesByPlayer($player);
        $unlocked = $this->zoneProgressRepo->findUnlockedZonesByPlayer($player);

        return array_merge(
            array_map(fn($p) => $p->getZone(), $completed),
            array_map(fn($p) => $p->getZone(), $unlocked)
        );
    }

    /**
     * Obtenir toutes les zones complétées
     */
    public function getCompletedZones(Player $player): array
    {
        $progressions = $this->zoneProgressRepo->findCompletedZonesByPlayer($player);
        return array_map(fn($p) => $p->getZone(), $progressions);
    }

    /**
     * Obtenir la progression d'une zone
     */
    public function getZoneProgress(Player $player, Zone $zone): ?ZoneProgress
    {
        return $this->zoneProgressRepo->findByPlayerAndZone($player, $zone);
    }

    /**
     * Vérifier si une zone est complètement terminée
     * (toutes les questions répondues correctement)
     */
    public function isZoneFullyCompleted(Player $player, Zone $zone, int $totalQuestions): bool
    {
        $progress = $this->zoneProgressRepo->findByPlayerAndZone($player, $zone);

        if (!$progress) {
            return false;
        }

        return $progress->getQuestionsCorrect() === $totalQuestions && $progress->isCompleted();
    }

    /**
     * Réinitialiser la progression d'un joueur (pour une nouvelle aventure)
     */
    public function resetPlayerProgress(Player $player): void
    {
        $progressions = $this->zoneProgressRepo->findByPlayer($player);

        foreach ($progressions as $progress) {
            $this->em->remove($progress);
        }

        $this->em->flush();
        $this->initializeZonesForPlayer($player);
    }
}
