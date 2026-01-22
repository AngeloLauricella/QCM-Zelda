<?php

namespace App\Entity;

use App\Repository\GameEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameEventRepository::class)]
#[ORM\Table(name: 'game_events')]
class GameEvent
{
    public const TYPE_REWARD = 'reward';
    public const TYPE_CHALLENGE = 'challenge';
    public const TYPE_TREASURE = 'treasure';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private Zone $zone;

    #[ORM\Column(type: 'string', length: 100)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type = self::TYPE_REWARD;

    #[ORM\Column(type: 'integer')]
    private int $rewardHearts = 0;

    #[ORM\Column(type: 'integer')]
    private int $rewardPoints = 0;

    #[ORM\Column(type: 'integer')]
    private int $penaltyHearts = 0;

    #[ORM\Column(type: 'integer')]
    private int $penaltyPoints = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isOneTimeOnly = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'integer')]
    private int $displayOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getRewardHearts(): int
    {
        return $this->rewardHearts;
    }

    public function setRewardHearts(int $hearts): static
    {
        $this->rewardHearts = $hearts;
        return $this;
    }

    public function getRewardPoints(): int
    {
        return $this->rewardPoints;
    }

    public function setRewardPoints(int $points): static
    {
        $this->rewardPoints = $points;
        return $this;
    }

    public function getPenaltyHearts(): int
    {
        return $this->penaltyHearts;
    }

    public function setPenaltyHearts(int $hearts): static
    {
        $this->penaltyHearts = $hearts;
        return $this;
    }

    public function getPenaltyPoints(): int
    {
        return $this->penaltyPoints;
    }

    public function setPenaltyPoints(int $points): static
    {
        $this->penaltyPoints = $points;
        return $this;
    }

    public function isOneTimeOnly(): bool
    {
        return $this->isOneTimeOnly;
    }

    public function setIsOneTimeOnly(bool $oneTimeOnly): static
    {
        $this->isOneTimeOnly = $oneTimeOnly;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $active): static
    {
        $this->isActive = $active;
        return $this;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $order): static
    {
        $this->displayOrder = $order;
        return $this;
    }
}
