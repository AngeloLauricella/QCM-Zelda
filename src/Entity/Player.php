<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: 'players')]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private string $name = '';

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Assert\Email(message: 'L\'email n\'est pas valide')]
    private string $email = '';

    #[ORM\Column(type: 'integer', options: ['default' => 3])]
    private int $hearts = 3;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\OneToOne(targetEntity: Score::class, mappedBy: 'player', cascade: ['persist', 'remove'])]
    private ?Score $score = null;

    #[ORM\OneToOne(targetEntity: GameProgress::class, mappedBy: 'player', cascade: ['all'])]
    private ?GameProgress $currentProgress = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->hearts = 3;
    }

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getHearts(): int
    {
        return $this->hearts;
    }

    public function setHearts(int $hearts): static
    {
        $this->hearts = max(0, $hearts);
        return $this;
    }

    public function isGameOver(): bool
    {
        return $this->hearts <= 0;
    }

    public function getScore(): int
    {
        return $this->score ? $this->score->getValue() ?? 0 : 0;
    }

    public function getScoreEntity(): ?Score
    {
        return $this->score;
    }

    public function setScoreEntity(?Score $scoreEntity): static
    {
        $this->score = $scoreEntity;
        if ($scoreEntity) {
            $scoreEntity->setPlayer($this);
        }
        return $this;
    }

    public function addScore(int $points): static
    {
        if (!$this->score) {
            $score = new Score();
            $score->setPlayer($this);
            $score->setValue(0);
            $this->score = $score;
        }
        $newValue = max(0, ($this->score->getValue() ?? 0) + $points);
        $this->score->setValue($newValue);
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getCurrentProgress(): ?GameProgress
    {
        return $this->currentProgress;
    }

    public function setCurrentProgress(?GameProgress $progress): static
    {
        $this->currentProgress = $progress;
        return $this;
    }
}
