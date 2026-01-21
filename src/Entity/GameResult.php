<?php

namespace App\Entity;

use App\Repository\GameResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité GameResult - Représente le résultat d'une partie
 */
#[ORM\Entity(repositoryClass: GameResultRepository::class)]
#[ORM\Table(name: 'game_results')]
#[ORM\Index(columns: ['player_id'], name: 'idx_player')]
#[ORM\Index(columns: ['question_id'], name: 'idx_question')]
class GameResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(name: 'player_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Player $player;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Question $question;

    #[ORM\Column(type: 'string', length: 1)]
    private string $userAnswer = '';

    #[ORM\Column(type: 'boolean')]
    private bool $isCorrect = false;

    #[ORM\Column(type: 'integer')]
    private int $pointsEarned = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $answeredAt;

    #[ORM\Column(type: 'integer')]
    private int $scoreAfter = 0;

    public function __construct(Player $player, Question $question)
    {
        $this->player = $player;
        $this->question = $question;
        $this->answeredAt = new \DateTimeImmutable();
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

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): static
    {
        $this->question = $question;
        return $this;
    }

    public function getUserAnswer(): string
    {
        return $this->userAnswer;
    }

    public function setUserAnswer(string $userAnswer): static
    {
        $this->userAnswer = strtoupper($userAnswer);
        $this->isCorrect = $this->question->isCorrectAnswer($this->userAnswer);
        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function getPointsEarned(): int
    {
        return $this->pointsEarned;
    }

    public function setPointsEarned(int $pointsEarned): static
    {
        $this->pointsEarned = $pointsEarned;
        return $this;
    }

    public function getAnsweredAt(): \DateTimeImmutable
    {
        return $this->answeredAt;
    }

    public function getScoreAfter(): int
    {
        return $this->scoreAfter;
    }

    public function setScoreAfter(int $scoreAfter): static
    {
        $this->scoreAfter = $scoreAfter;
        return $this;
    }
}
