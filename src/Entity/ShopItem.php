<?php

namespace App\Entity;

use App\Repository\ShopItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShopItemRepository::class)]
#[ORM\Table(name: 'shop_items')]
class ShopItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trophy::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Trophy $trophy;

    #[ORM\Column(type: 'integer')]
    private int $cost = 0;

    #[ORM\Column(type: 'integer')]
    private int $stock = -1;

    #[ORM\Column(type: 'boolean')]
    private bool $isAvailable = true;

    #[ORM\Column(type: 'integer')]
    private int $displayOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrophy(): Trophy
    {
        return $this->trophy;
    }

    public function setTrophy(Trophy $trophy): static
    {
        $this->trophy = $trophy;
        return $this;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): static
    {
        $this->cost = $cost;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable && ($this->stock === -1 || $this->stock > 0);
    }

    public function setIsAvailable(bool $available): static
    {
        $this->isAvailable = $available;
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

    public function decrementStock(): static
    {
        if ($this->stock !== -1) {
            $this->stock = max(0, $this->stock - 1);
        }
        return $this;
    }

    public function hasUnlimitedStock(): bool
    {
        return $this->stock === -1;
    }
}
