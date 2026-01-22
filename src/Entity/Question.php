<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'questions')]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Zone $zone = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    private string $title = '';

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    private string $description = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'L\'option A est obligatoire')]
    private string $optionA = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'L\'option B est obligatoire')]
    private string $optionB = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'L\'option C est obligatoire')]
    private string $optionC = '';

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'L\'option D est obligatoire')]
    private string $optionD = '';

    #[ORM\Column(type: 'string', length: 1)]
    #[Assert\Choice(choices: ['A', 'B', 'C', 'D'], message: 'La réponse doit être A, B, C ou D')]
    private string $correctAnswer = '';

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(message: 'La catégorie est obligatoire')]
    private string $category = ''; // 'introduction', 'foret', 'montagne', 'bonus'

    #[ORM\Column(type: 'integer')]
    private int $displayOrder = 0;

    #[ORM\Column(type: 'integer')]
    private int $pointsValue = 3;

    #[ORM\Column(type: 'integer')]
    private int $rewardHearts = 0;

    #[ORM\Column(type: 'integer')]
    private int $rewardPoints = 0;

    #[ORM\Column(type: 'integer')]
    private int $penaltyHearts = 1;

    #[ORM\Column(type: 'integer')]
    private int $penaltyPoints = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isOneTimeOnly = false;

    #[ORM\Column(type: 'integer')]
    private int $step = 1;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;
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

    public function getOptionA(): string
    {
        return $this->optionA;
    }

    public function setOptionA(string $optionA): static
    {
        $this->optionA = $optionA;
        return $this;
    }

    public function getOptionB(): string
    {
        return $this->optionB;
    }

    public function setOptionB(string $optionB): static
    {
        $this->optionB = $optionB;
        return $this;
    }

    public function getOptionC(): string
    {
        return $this->optionC;
    }

    public function setOptionC(string $optionC): static
    {
        $this->optionC = $optionC;
        return $this;
    }

    public function getOptionD(): string
    {
        return $this->optionD;
    }

    public function setOptionD(string $optionD): static
    {
        $this->optionD = $optionD;
        return $this;
    }

    public function getCorrectAnswer(): string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(string $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;
        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }

    public function getPointsValue(): int
    {
        return $this->pointsValue;
    }

    public function setPointsValue(int $pointsValue): static
    {
        $this->pointsValue = $pointsValue;
        return $this;
    }

    public function getOptions(): array
    {
        return [
            'A' => $this->optionA,
            'B' => $this->optionB,
            'C' => $this->optionC,
            'D' => $this->optionD,
        ];
    }

    public function isCorrectAnswer(string $answer): bool
    {
        return strtoupper($answer) === $this->correctAnswer;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    public function setStep(int $step): static
    {
        $this->step = $step;
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
}
