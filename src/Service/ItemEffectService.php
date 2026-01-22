<?php

namespace App\Service;

use App\Entity\Equipment;
use App\Entity\Trophy;

class ItemEffectService
{
    public function calculateInitialHearts(Equipment $equipment): int
    {
        $baseHearts = 3;
        $bonus = 0;

        foreach ($equipment->getEquippedItems() as $item) {
            $bonus += $item->getHeartBonus();
        }

        $totalHearts = $baseHearts + $bonus;
        return min(5, max(0, $totalHearts));
    }

    public function calculatePointsMultiplier(Equipment $equipment): float
    {
        $multiplier = 1.0;
        $appliedTypes = [];

        foreach ($equipment->getEquippedItems() as $item) {
            $itemMultiplier = $item->getPointsMultiplier();
            
            if ($itemMultiplier > 1.0) {
                $typeKey = 'boost_' . md5($item->getType());
                
                if (!isset($appliedTypes[$typeKey])) {
                    $appliedTypes[$typeKey] = true;
                    $multiplier *= $itemMultiplier;
                }
            }
        }

        return min(3.0, $multiplier);
    }

    public function hasStackableEffect(Equipment $equipment, Trophy $newItem): bool
    {
        $equippedItems = $equipment->getEquippedItems();
        
        foreach ($equippedItems as $item) {
            if ($item->getId() === $newItem->getId()) {
                return false;
            }

            if ($item->getType() === $newItem->getType() && $item->getPointsMultiplier() > 1.0) {
                if ($newItem->getPointsMultiplier() > 1.0) {
                    return false;
                }
            }
        }

        return true;
    }

    public function canEquipWithoutStacking(Equipment $equipment, Trophy $item): bool
    {
        if ($item->isPassive()) {
            $passiveCount = count(array_filter($equipment->getEquippedItems(), fn($t) => $t->isPassive()));
            if ($passiveCount >= 3) {
                return false;
            }
        }

        return $this->hasStackableEffect($equipment, $item);
    }
}
