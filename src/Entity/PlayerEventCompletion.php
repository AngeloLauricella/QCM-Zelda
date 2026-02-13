<?php

namespace App\Entity;

use App\Repository\PlayerEventCompletionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerEventCompletionRepository::class)]
#[ORM\Table(name: 'player_event_completions')]
#[ORM\UniqueConstraint(name: 'unique_player_event', columns: ['game_progress_id', 'game_event_id'])]
class PlayerEventCompletion
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: GameProgress::class, inversedBy: 'eventCompletions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?GameProgress $gameProgress = null;


    #[ORM\ManyToOne(targetEntity: GameEvent::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?GameEvent $gameEvent = null;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Question $question = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $completedAt;

    #[ORM\Column(type: 'integer')]
    private int $heartsEarned = 0;

    #[ORM\Column(type: 'integer')]
    private int $pointsEarned = 0;

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

    public function getGameEvent(): ?GameEvent
    {
        return $this->gameEvent;
    }

    public function setGameEvent(?GameEvent $event): static
    {
        $this->gameEvent = $event;
        return $this;
    }

    public function getCompletedAt(): \DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;
        return $this;
    }

    public function getHeartsEarned(): int
    {
        return $this->heartsEarned;
    }

    public function setHeartsEarned(int $hearts): static
    {
        $this->heartsEarned = $hearts;
        return $this;
    }

    public function getPointsEarned(): int
    {
        return $this->pointsEarned;
    }

    public function setPointsEarned(int $points): static
    {
        $this->pointsEarned = $points;
        return $this;
    }
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;
        return $this;
    }

}
