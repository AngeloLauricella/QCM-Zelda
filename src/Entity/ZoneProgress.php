<?php

namespace App\Entity;

use App\Repository\ZoneProgressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneProgressRepository::class)]
#[ORM\Table(name: 'zone_progress')]
#[ORM\UniqueConstraint(name: 'unique_progress', columns: ['player_id', 'zone_id'])]
class ZoneProgress
{
    public const STATUS_LOCKED = 'locked';
    public const STATUS_UNLOCKED = 'unlocked';
    public const STATUS_COMPLETED = 'completed';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Player $player;

    #[ORM\ManyToOne(targetEntity: Zone::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Zone $zone;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = self::STATUS_LOCKED;

    #[ORM\Column(type: 'integer')]
    private int $questionsAnswered = 0;

    #[ORM\Column(type: 'integer')]
    private int $questionsCorrect = 0;

    #[ORM\Column(type: 'integer')]
    private int $zoneScore = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $startedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(Player $player, Zone $zone, string $initialStatus = self::STATUS_LOCKED)
    {
        $this->player = $player;
        $this->zone = $zone;
        $this->status = $initialStatus;
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

    public function setPlayer(Player $player): static
    {
        $this->player = $player;
        return $this;
    }

    public function getZone(): Zone
    {
        return $this->zone;
    }

    public function setZone(Zone $zone): static
    {
        $this->zone = $zone;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        if (!in_array($status, [self::STATUS_LOCKED, self::STATUS_UNLOCKED, self::STATUS_COMPLETED])) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid status "%s". Must be one of: %s',
                $status,
                implode(', ', [self::STATUS_LOCKED, self::STATUS_UNLOCKED, self::STATUS_COMPLETED])
            ));
        }
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_LOCKED;
    }

    public function isUnlocked(): bool
    {
        return $this->status === self::STATUS_UNLOCKED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function unlock(): static
    {
        return $this->setStatus(self::STATUS_UNLOCKED);
    }

    public function complete(): static
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getQuestionsAnswered(): int
    {
        return $this->questionsAnswered;
    }

    public function setQuestionsAnswered(int $count): static
    {
        $this->questionsAnswered = max(0, $count);
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function incrementQuestionsAnswered(): static
    {
        $this->questionsAnswered++;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getQuestionsCorrect(): int
    {
        return $this->questionsCorrect;
    }

    public function setQuestionsCorrect(int $count): static
    {
        $this->questionsCorrect = max(0, $count);
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function incrementQuestionsCorrect(): static
    {
        $this->questionsCorrect++;
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getZoneScore(): int
    {
        return $this->zoneScore;
    }

    public function setZoneScore(int $score): static
    {
        $this->zoneScore = max(0, $score);
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function addZoneScore(int $points): static
    {
        $this->zoneScore = max(0, $this->zoneScore + $points);
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getProgressPercentage(): int
    {
        if ($this->questionsAnswered === 0) {
            return 0;
        }
        return (int) round(($this->questionsCorrect / $this->questionsAnswered) * 100);
    }

    /**
     * Calcule le pourcentage d'avancement (questionsAnswered / total)
     */
    public function getProgressPercentageFromTotal(int $totalQuestions): int
    {
        if ($totalQuestions === 0) {
            return 0;
        }
        return (int) round(($this->questionsAnswered / $totalQuestions) * 100);
    }

    /**
     * Vérifie si la zone est complètement terminée
     */
    public function isFullyAnswered(int $totalQuestions): bool
    {
        return $this->questionsAnswered >= $totalQuestions;
    }
}
