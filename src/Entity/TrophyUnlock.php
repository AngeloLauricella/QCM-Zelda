<?php

namespace App\Entity;

use App\Repository\TrophyUnlockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrophyUnlockRepository::class)]
#[ORM\Table(name: 'trophy_unlocks')]
#[ORM\UniqueConstraint(name: 'unique_player_trophy', columns: ['game_progress_id', 'trophy_id'])]
class TrophyUnlock
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

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $unlockedAt;

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

    public function getUnlockedAt(): \DateTimeImmutable
    {
        return $this->unlockedAt;
    }

    public function setUnlockedAt(\DateTimeImmutable $at): static
    {
        $this->unlockedAt = $at;
        return $this;
    }
}
