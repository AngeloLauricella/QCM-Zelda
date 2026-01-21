<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entité Question - Représente une question du jeu avec ses options et réponse
 */
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'questions')]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
    private int $pointsValue = 3; // Points gagnés si correct

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * Retourne les options de la question sous forme de tableau
     */
    public function getOptions(): array
    {
        return [
            'A' => $this->optionA,
            'B' => $this->optionB,
            'C' => $this->optionC,
            'D' => $this->optionD,
        ];
    }

    /**
     * Vérifie si la réponse fournie est correcte
     */
    public function isCorrectAnswer(string $answer): bool
    {
        return strtoupper($answer) === $this->correctAnswer;
    }
}
