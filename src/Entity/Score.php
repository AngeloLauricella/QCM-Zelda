<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité Score - Représente le score global d'un joueur (haute score)
 * 
 * Note: Cette entité stocke le MEILLEUR score du joueur (high score).
 * Pour l'historique complet des résultats, voir GameResult.php
 */
#[ORM\Entity(repositoryClass: ScoreRepository::class)]
#[ORM\Table(name: 'score')]
#[ORM\Index(columns: ['player_id'], name: 'idx_score_player')]
#[ORM\Index(columns: ['value'], name: 'idx_score_value')]
#[ORM\Index(columns: ['updated_at'], name: 'idx_score_updated_at')]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Player::class, inversedBy: 'score')]
    #[ORM\JoinColumn(name: 'player_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE', unique: true)]
    private ?Player $player = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $value = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $questionsCorrect = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $questionsAttempted = 0;

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->value = 0;
        $this->questionsCorrect = 0;
        $this->questionsAttempted = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;
        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Le score ne peut pas être négatif');
        }
        $this->value = $value;
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * Ajoute des points au score existant
     */
    public function addPoints(int $points): static
    {
        if ($points < 0) {
            throw new \InvalidArgumentException('Les points à ajouter ne peuvent pas être négatifs');
        }
        $this->value += $points;
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * Calcule le pourcentage de réussite
     */
    public function getSuccessPercentage(): float
    {
        if ($this->questionsAttempted === 0) {
            return 0.0;
        }
        return ($this->questionsCorrect / $this->questionsAttempted) * 100;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getQuestionsCorrect(): int
    {
        return $this->questionsCorrect;
    }

    public function setQuestionsCorrect(int $questionsCorrect): static
    {
        if ($questionsCorrect < 0) {
            throw new \InvalidArgumentException('Le nombre de questions correctes ne peut pas être négatif');
        }
        $this->questionsCorrect = $questionsCorrect;
        return $this;
    }

    public function incrementQuestionsCorrect(): static
    {
        $this->questionsCorrect++;
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }

    public function getQuestionsAttempted(): int
    {
        return $this->questionsAttempted;
    }

    public function setQuestionsAttempted(int $questionsAttempted): static
    {
        if ($questionsAttempted < 0) {
            throw new \InvalidArgumentException('Le nombre de questions tentées ne peut pas être négatif');
        }
        $this->questionsAttempted = $questionsAttempted;
        return $this;
    }

    public function incrementQuestionsAttempted(): static
    {
        $this->questionsAttempted++;
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }
}
