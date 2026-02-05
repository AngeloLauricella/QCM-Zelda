<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
#[ORM\Table(name: 'player_equipment')]
class Equipment
{
    public const SLOT_WEAPON = 'weapon';
    public const SLOT_OBJECT_1 = 'object_1';
    public const SLOT_OBJECT_2 = 'object_2';
    public const SLOT_OBJECT_3 = 'object_3';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: GameProgress::class, inversedBy: 'equipment')]
    #[ORM\JoinColumn(nullable: false)]
    private GameProgress $gameProgress;

    #[ORM\ManyToOne(targetEntity: Trophy::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Trophy $weaponEquipped = null;

    #[ORM\ManyToOne(targetEntity: Trophy::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Trophy $object1 = null;

    #[ORM\ManyToOne(targetEntity: Trophy::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Trophy $object2 = null;

    #[ORM\ManyToOne(targetEntity: Trophy::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Trophy $object3 = null;

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

    public function getWeaponEquipped(): ?Trophy
    {
        return $this->weaponEquipped;
    }

    public function setWeaponEquipped(?Trophy $weapon): static
    {
        $this->weaponEquipped = $weapon;
        return $this;
    }

    public function getObject1(): ?Trophy
    {
        return $this->object1;
    }

    public function setObject1(?Trophy $object): static
    {
        $this->object1 = $object;
        return $this;
    }

    public function getObject2(): ?Trophy
    {
        return $this->object2;
    }

    public function setObject2(?Trophy $object): static
    {
        $this->object2 = $object;
        return $this;
    }

    public function getObject3(): ?Trophy
    {
        return $this->object3;
    }

    public function setObject3(?Trophy $object): static
    {
        $this->object3 = $object;
        return $this;
    }

    public function getEquippedItems(): array
    {
        $items = [];
        if ($this->weaponEquipped) {
            $items[] = $this->weaponEquipped;
        }
        if ($this->object1) {
            $items[] = $this->object1;
        }
        if ($this->object2) {
            $items[] = $this->object2;
        }
        if ($this->object3) {
            $items[] = $this->object3;
        }
        return $items;
    }

    public function getObjectSlots(): array
    {
        return [
            self::SLOT_OBJECT_1 => $this->object1,
            self::SLOT_OBJECT_2 => $this->object2,
            self::SLOT_OBJECT_3 => $this->object3,
        ];
    }

    public function canEquipItem(Trophy $item): bool
    {
        if ($item->isPassive()) {
            if (!$this->object1) return true;
            if (!$this->object2) return true;
            if (!$this->object3) return true;
            return false;
        }

        return true;
    }

    public function equipItem(Trophy $item, string $slot): bool
    {
        if ($slot === self::SLOT_WEAPON) {
            $this->weaponEquipped = $item;
            return true;
        }

        if ($slot === self::SLOT_OBJECT_1) {
            $this->object1 = $item;
            return true;
        }

        if ($slot === self::SLOT_OBJECT_2) {
            $this->object2 = $item;
            return true;
        }

        if ($slot === self::SLOT_OBJECT_3) {
            $this->object3 = $item;
            return true;
        }

        return false;
    }

    public function unequipItem(string $slot): bool
    {
        if ($slot === self::SLOT_WEAPON) {
            $this->weaponEquipped = null;
            return true;
        }

        if ($slot === self::SLOT_OBJECT_1) {
            $this->object1 = null;
            return true;
        }

        if ($slot === self::SLOT_OBJECT_2) {
            $this->object2 = null;
            return true;
        }

        if ($slot === self::SLOT_OBJECT_3) {
            $this->object3 = null;
            return true;
        }

        return false;
    }
}
