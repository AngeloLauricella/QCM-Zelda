<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TempleForetFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Temple de la Forêt']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Temple de la Forêt');
            $zone->setDescription('Explorez le temple maudit et sauvez Saria pour éveiller le premier Sage.');
            $zone->setDisplayOrder(7);
            $zone->setMinPointsToUnlock(550);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Quel est l’état du Village Kokiri après le saut temporel ?',
                'description' => 'Situation constatée par Link adulte.',
                'optionA' => 'Paisible',
                'optionB' => 'Envahi par des monstres',
                'optionC' => 'Abandonné',
                'optionD' => 'Détruit',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel ennemi apparaît fréquemment dans le Temple de la Forêt ?',
                'description' => 'Chevaliers squelettiques redoutables.',
                'optionA' => 'ReDead',
                'optionB' => 'Stalfos',
                'optionC' => 'Iron Knuckle',
                'optionD' => 'Lizalfos',
                'correctAnswer' => 'B',
                'category' => 'Ennemis',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quelle particularité architecturale rend le temple unique ?',
                'description' => 'Élément central des énigmes.',
                'optionA' => 'Salles inondées',
                'optionB' => 'Couloirs torsadés',
                'optionC' => 'Pièces en feu',
                'optionD' => 'Sable mouvant',
                'correctAnswer' => 'B',
                'category' => 'Donjon',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel objet clé Link obtient-il dans ce temple ?',
                'description' => 'Permet de traverser des gouffres.',
                'optionA' => 'Grappin',
                'optionB' => 'Arc des Fées',
                'optionC' => 'Marteau',
                'optionD' => 'Bottes de plomb',
                'correctAnswer' => 'A',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel est le boss du Temple de la Forêt ?',
                'description' => 'Serviteur spectral de Ganondorf.',
                'optionA' => 'Volvagia',
                'optionB' => 'Bongo Bongo',
                'optionC' => 'Phantom Ganon',
                'optionD' => 'Morpha',
                'correctAnswer' => 'C',
                'category' => 'Boss',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Comment Phantom Ganon attaque-t-il principalement ?',
                'description' => 'Mécanique principale du combat.',
                'optionA' => 'Il se téléporte derrière Link',
                'optionB' => 'Il sort de tableaux à cheval',
                'optionC' => 'Il invoque des flammes',
                'optionD' => 'Il manipule l’eau',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel Sage est éveillé après la victoire ?',
                'description' => 'Premier Sage adulte éveillé.',
                'optionA' => 'Ruto',
                'optionB' => 'Impa',
                'optionC' => 'Saria',
                'optionD' => 'Nabooru',
                'correctAnswer' => 'C',
                'category' => 'Sages',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel médaillon Link reçoit-il ?',
                'description' => 'Symbole du Sage éveillé.',
                'optionA' => 'Médaillon du Feu',
                'optionB' => 'Médaillon de la Forêt',
                'optionC' => 'Médaillon de l’Eau',
                'optionD' => 'Médaillon de l’Ombre',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel personnage mystérieux guide Link adulte ?',
                'description' => 'Identité cachée pendant une grande partie du jeu.',
                'optionA' => 'Rauru',
                'optionB' => 'Darunia',
                'optionC' => 'Sheik',
                'optionD' => 'Zelda',
                'correctAnswer' => 'C',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel est l’objectif global après ce temple ?',
                'description' => 'Mission donnée par Rauru.',
                'optionA' => 'Vaincre Ganondorf immédiatement',
                'optionB' => 'Éveiller les autres Sages',
                'optionC' => 'Retourner enfant',
                'optionD' => 'Trouver la Triforce',
                'correctAnswer' => 'B',
                'category' => 'Progression',
                'pointsValue' => 20,
            ],
        ];

        foreach ($questions as $index => $qData) {
            $question = new Question();
            $question->setZone($zone);
            $question->setTitle($qData['title']);
            $question->setDescription($qData['description']);
            $question->setOptionA($qData['optionA']);
            $question->setOptionB($qData['optionB']);
            $question->setOptionC($qData['optionC']);
            $question->setOptionD($qData['optionD']);
            $question->setCorrectAnswer($qData['correctAnswer']);
            $question->setCategory($qData['category']);
            $question->setPointsValue($qData['pointsValue']);
            $question->setRewardHearts(0);
            $question->setRewardPoints($qData['pointsValue']);
            $question->setPenaltyHearts(0);
            $question->setPenaltyPoints(0);
            $question->setDisplayOrder($index + 1);
            $question->setIsActive(true);
            $question->setStep(1);
            $manager->persist($question);
        }

        $manager->flush();
    }
}