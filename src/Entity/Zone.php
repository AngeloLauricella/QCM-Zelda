<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[ORM\Table(name: 'zones')]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'integer')]
    private int $displayOrder = 0;

    #[ORM\Column(type: 'integer')]
    private int $minPointsToUnlock = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'zone', cascade: ['persist'])]
    private Collection $questions;

    #[ORM\OneToMany(targetEntity: GameEvent::class, mappedBy: 'zone', cascade: ['persist'])]
    private Collection $events;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
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

    public function getMinPointsToUnlock(): int
    {
        return $this->minPointsToUnlock;
    }

    public function setMinPointsToUnlock(int $points): static
    {
        $this->minPointsToUnlock = $points;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $active): static
    {
        $this->isActive = $active;
        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setZone($this);
        }
        return $this;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(GameEvent $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setZone($this);
        }
        return $this;
    }
}
