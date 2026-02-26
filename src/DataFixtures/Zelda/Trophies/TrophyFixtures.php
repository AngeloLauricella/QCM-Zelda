<?php

namespace App\DataFixtures\Zelda\Trophies;

use App\Entity\Trophy;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TrophyFixtures extends Fixture 
{

    public function load(ObjectManager $manager): void
    {
        $trophies = [
            [
                'name' => 'Épée de Kokiri',
                'description' => 'Une épée légère mais tranchante, utilisée par les Kokiri.',
                'type' => Trophy::TYPE_PASSIVE,
                'heartBonus' => 0,
                'pointsMultiplier' => 1.1,
                'icon' => 'eppee_kokiri.png',
                'unlockCondition' => 'Complétez la Forêt Perdue',
            ],
            [
                'name' => 'Master Sword',
                'description' => 'L\'Épée Maîtresse, arme légendaire capable de sceller le Mal.',
                'type' => Trophy::TYPE_ACTIVE,
                'heartBonus' => 1,
                'pointsMultiplier' => 1.5,
                'icon' => 'master_sword.png',
                'unlockCondition' => 'Gagnez 500 points',
            ],
            [
                'name' => 'Bouclier Hylia',
                'description' => 'Un bouclier sacré avec le symbole d\'Hyrule.',
                'type' => Trophy::TYPE_PASSIVE,
                'heartBonus' => 1,
                'pointsMultiplier' => 1.0,
                'icon' => 'bouclier_hylien.png',
                'unlockCondition' => 'Protégez-vous 10 fois',
            ],
            [
                'name' => 'Ocarina du Temps',
                'description' => 'Instrument magique capable de manipuler le temps.',
                'type' => Trophy::TYPE_ACTIVE,
                'heartBonus' => 2,
                'pointsMultiplier' => 2.0,
                'icon' => 'ocarina_du_temps.png',
                'unlockCondition' => 'Maîtrisez 5 mélodies',
            ],
            [
                'name' => 'Triforce',
                'description' => 'La Triforce sacrée, source ultime de pouvoir.',
                'type' => Trophy::TYPE_ACTIVE,
                'heartBonus' => 3,
                'pointsMultiplier' => 3.0,
                'icon' => 'triforce.png',
                'unlockCondition' => 'Battez Ganondorf',
            ]
        ];

        foreach ($trophies as $index => $data) {

            // 🔥 Anti doublon si tu relances les fixtures
            $existing = $manager->getRepository(Trophy::class)
                ->findOneBy(['name' => $data['name']]);

            if ($existing) {
                continue;
            }

            $trophy = new Trophy();
            $trophy->setName($data['name']);
            $trophy->setDescription($data['description']);
            $trophy->setType($data['type']);
            $trophy->setHeartBonus($data['heartBonus']);
            $trophy->setPointsMultiplier($data['pointsMultiplier']);
            $trophy->setIcon($data['icon']);
            $trophy->setUnlockCondition($data['unlockCondition']);
            $trophy->setIsVisible(true);
            $trophy->setDisplayOrder($index + 1);

            $manager->persist($trophy);
        }

        $manager->flush();
    }
}
