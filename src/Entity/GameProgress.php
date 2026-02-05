<?php

namespace App\Entity;

use App\Repository\GameProgressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameProgressRepository::class)]
#[ORM\Table(name: 'game_progress')]
class GameProgress
{
    public const INITIAL_HEARTS = 3;
    public const MAX_HEARTS = 5;
    public const INITIAL_POINTS = 0;
    public const INTRODUCTION_ZONE_ID = 1;
    public const FOREST_ZONE_ID = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Player::class, inversedBy: 'currentProgress')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Player $player;

    #[ORM\Column(type: 'integer')]
    private int $hearts = self::INITIAL_HEARTS;

    #[ORM\Column(type: 'integer')]
    private int $points = self::INITIAL_POINTS;

    #[ORM\Column(type: 'integer')]
    private int $currentZoneId = 1;

    #[ORM\Column(type: 'boolean')]
    private bool $isGameOver = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $gameOverReason = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $startedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\OneToOne(targetEntity: Equipment::class, mappedBy: 'gameProgress', cascade: ['persist', 'remove'])]
    private ?Equipment $equipment = null;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->hearts = self::INITIAL_HEARTS;
        $this->points = self::INITIAL_POINTS;
        $this->currentZoneId = self::INTRODUCTION_ZONE_ID;  // Will be set properly in startNewAdventure()
        $this->isGameOver = false;
        $this->startedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getHearts(): int
    {
        return $this->hearts;
    }

    public function setHearts(int $hearts): static
    {
        $this->hearts = max(0, min($hearts, self::MAX_HEARTS));
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function addHearts(int $amount): static
    {
        return $this->setHearts($this->hearts + $amount);
    }

    public function removeHearts(int $amount): static
    {
        return $this->setHearts($this->hearts - $amount);
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = max(0, $points);
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function addPoints(int $amount): static
    {
        return $this->setPoints($this->points + $amount);
    }

    public function removePoints(int $amount): static
    {
        return $this->setPoints($this->points - $amount);
    }

    public function getCurrentZoneId(): int
    {
        return $this->currentZoneId;
    }

    public function setCurrentZoneId(int $zoneId): static
    {
        $this->currentZoneId = $zoneId;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }

    public function setGameOver(bool $isGameOver, ?string $reason = null): static
    {
        $this->isGameOver = $isGameOver;
        $this->gameOverReason = $reason;
        if ($isGameOver) {
            $this->endedAt = new \DateTimeImmutable();
        }
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getGameOverReason(): ?string
    {
        return $this->gameOverReason;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public static function getInitialHearts(): int
    {
        return self::INITIAL_HEARTS;
    }

    public static function getMaxHearts(): int
    {
        return self::MAX_HEARTS;
    }

    public function hasStarted(): bool
    {
        return true;
    }

    public function reset(): static
    {
        $this->hearts = self::INITIAL_HEARTS;
        $this->points = self::INITIAL_POINTS;
        $this->currentZoneId = 1;
        $this->isGameOver = false;
        $this->gameOverReason = null;
        $this->endedAt = null;
        $this->startedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): static
    {
        $this->equipment = $equipment;
        return $this;
    }
}
