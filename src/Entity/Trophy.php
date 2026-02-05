<?php

namespace App\Entity;

use App\Repository\TrophyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrophyRepository::class)]
#[ORM\Table(name: 'trophies')]
class Trophy
{
    public const TYPE_PASSIVE = 'passive';
    public const TYPE_ACTIVE = 'active';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type = self::TYPE_PASSIVE;

    #[ORM\Column(type: 'text')]
    private string $unlockCondition = '';

    #[ORM\Column(type: 'integer')]
    private int $heartBonus = 0;

    #[ORM\Column(type: 'float')]
    private float $pointsMultiplier = 1.0;

    #[ORM\Column(type: 'string', length: 100)]
    private string $icon = '';

    #[ORM\Column(type: 'boolean')]
    private bool $isVisible = true;

    #[ORM\Column(type: 'integer')]
    private int $displayOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
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

    public function getUnlockCondition(): string
    {
        return $this->unlockCondition;
    }

    public function setUnlockCondition(string $condition): static
    {
        $this->unlockCondition = $condition;
        return $this;
    }

    public function getHeartBonus(): int
    {
        return $this->heartBonus;
    }

    public function setHeartBonus(int $bonus): static
    {
        $this->heartBonus = $bonus;
        return $this;
    }

    public function getPointsMultiplier(): float
    {
        return $this->pointsMultiplier;
    }

    public function setPointsMultiplier(float $multiplier): static
    {
        $this->pointsMultiplier = $multiplier;
        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $visible): static
    {
        $this->isVisible = $visible;
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

    public function isPassive(): bool
    {
        return $this->type === self::TYPE_PASSIVE;
    }

    public function isActive(): bool
    {
        return $this->type === self::TYPE_ACTIVE;
    }
}
