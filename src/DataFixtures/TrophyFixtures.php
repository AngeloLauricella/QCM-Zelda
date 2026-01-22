<?php

namespace App\DataFixtures;

use App\Entity\Trophy;
use App\Entity\ShopItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrophyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $trophies = [
            [
                'name' => 'Cape Bleue',
                'description' => 'Augmente les cœurs au démarrage',
                'type' => Trophy::TYPE_PASSIVE,
                'unlockCondition' => 'Complétez la première zone',
                'heartBonus' => 1,
                'pointsMultiplier' => 1.0,
                'icon' => '🧥',
                'displayOrder' => 1,
            ],
            [
                'name' => 'Boots de Vitesse',
                'description' => 'Double les points gagnés',
                'type' => Trophy::TYPE_PASSIVE,
                'unlockCondition' => 'Obtenez 100 points',
                'heartBonus' => 0,
                'pointsMultiplier' => 2.0,
                'icon' => '👢',
                'displayOrder' => 2,
            ],
            [
                'name' => 'Cœur Supplémentaire',
                'description' => '+1 cœur au démarrage',
                'type' => Trophy::TYPE_PASSIVE,
                'unlockCondition' => 'Terminez sans perdre de cœur',
                'heartBonus' => 1,
                'pointsMultiplier' => 1.0,
                'icon' => '💚',
                'displayOrder' => 3,
            ],
            [
                'name' => 'Anneau de Sagesse',
                'description' => '+1.5x points multiplicateur',
                'type' => Trophy::TYPE_PASSIVE,
                'unlockCondition' => 'Obtenez 250 points',
                'heartBonus' => 0,
                'pointsMultiplier' => 1.5,
                'icon' => '💎',
                'displayOrder' => 4,
            ],
            [
                'name' => 'Masque du Héros',
                'description' => 'Vous rend plus fort',
                'type' => Trophy::TYPE_ACTIVE,
                'unlockCondition' => 'Déverrouillez un secret',
                'heartBonus' => 0,
                'pointsMultiplier' => 1.0,
                'icon' => '😷',
                'displayOrder' => 5,
            ],
        ];

        $savedTrophies = [];
        foreach ($trophies as $data) {
            $trophy = new Trophy();
            $trophy->setName($data['name']);
            $trophy->setDescription($data['description']);
            $trophy->setType($data['type']);
            $trophy->setUnlockCondition($data['unlockCondition']);
            $trophy->setHeartBonus($data['heartBonus']);
            $trophy->setPointsMultiplier($data['pointsMultiplier']);
            $trophy->setIcon($data['icon']);
            $trophy->setDisplayOrder($data['displayOrder']);
            $trophy->setIsVisible(true);

            $manager->persist($trophy);
            $savedTrophies[] = $trophy;
        }

        $manager->flush();

        $shopItems = [
            ['trophy' => $savedTrophies[0], 'cost' => 50, 'stock' => -1],
            ['trophy' => $savedTrophies[1], 'cost' => 100, 'stock' => -1],
            ['trophy' => $savedTrophies[2], 'cost' => 75, 'stock' => 5],
            ['trophy' => $savedTrophies[3], 'cost' => 150, 'stock' => 3],
            ['trophy' => $savedTrophies[4], 'cost' => 200, 'stock' => 2],
        ];

        foreach ($shopItems as $index => $data) {
            $shopItem = new ShopItem();
            $shopItem->setTrophy($data['trophy']);
            $shopItem->setCost($data['cost']);
            $shopItem->setStock($data['stock']);
            $shopItem->setIsAvailable(true);
            $shopItem->setDisplayOrder($index);

            $manager->persist($shopItem);
        }

        $manager->flush();
    }
}
