<?php

namespace App\Entity;

use App\Repository\PurchaseHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseHistoryRepository::class)]
#[ORM\Table(name: 'purchase_history')]
class PurchaseHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: GameProgress::class)]
    #[ORM\JoinColumn(nullable: false)]
    private GameProgress $gameProgress;

    #[ORM\ManyToOne(targetEntity: Trophy::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Trophy $trophy;

    #[ORM\Column(type: 'integer')]
    private int $costPaid = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $purchasedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameProgress(): GameProgress
    {
        return $this->gameProgress;
    }

    public function setGameProgress(GameProgress $progress): static
    {
        $this->gameProgress = $progress;
        return $this;
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

    public function getCostPaid(): int
    {
        return $this->costPaid;
    }

    public function setCostPaid(int $cost): static
    {
        $this->costPaid = $cost;
        return $this;
    }

    public function getPurchasedAt(): \DateTimeImmutable
    {
        return $this->purchasedAt;
    }

    public function setPurchasedAt(\DateTimeImmutable $at): static
    {
        $this->purchasedAt = $at;
        return $this;
    }
}
