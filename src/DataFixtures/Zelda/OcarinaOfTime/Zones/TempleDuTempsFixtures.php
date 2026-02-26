<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TempleDuTempsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Temple du Temps']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Temple du Temps');
            $zone->setDescription('Ouvrez la Porte du Temps et découvrez la vérité sur le destin de Link.');
            $zone->setDisplayOrder(6);
            $zone->setMinPointsToUnlock(450);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Quelle chanson ouvre la Porte du Temps ?',
                'description' => 'Apprise auprès de la Princesse Zelda.',
                'optionA' => 'Chant de Saria',
                'optionB' => 'Berceuse de Zelda',
                'optionC' => 'Chant du Temps',
                'optionD' => 'Boléro du Feu',
                'correctAnswer' => 'C',
                'category' => 'Chansons',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel objet se trouve derrière la Porte du Temps ?',
                'description' => 'L’arme légendaire scellée.',
                'optionA' => 'Ocarina du Temps',
                'optionB' => 'Arc sacré',
                'optionC' => 'Épée de Légende',
                'optionD' => 'Trident Gerudo',
                'correctAnswer' => 'C',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Que se passe-t-il lorsque Link retire l’Épée de Légende ?',
                'description' => 'Conséquence immédiate.',
                'optionA' => 'Ganondorf est scellé',
                'optionB' => 'Link devient adulte',
                'optionC' => 'Le Temple s’effondre',
                'optionD' => 'Zelda disparaît',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Combien d’années Link est-il scellé ?',
                'description' => 'Durée du saut temporel.',
                'optionA' => '3 ans',
                'optionB' => '5 ans',
                'optionC' => '7 ans',
                'optionD' => '10 ans',
                'correctAnswer' => 'C',
                'category' => 'Histoire',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel Sage accueille Link à son réveil ?',
                'description' => 'Sage de la Lumière.',
                'optionA' => 'Darunia',
                'optionB' => 'Rauru',
                'optionC' => 'Nabooru',
                'optionD' => 'Impa',
                'correctAnswer' => 'B',
                'category' => 'Personnages',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel pouvoir Ganondorf obtient-il en entrant dans le Royaume Sacré ?',
                'description' => 'Élément central de la suite du jeu.',
                'optionA' => 'La totalité de la Triforce',
                'optionB' => 'La Triforce de la Force',
                'optionC' => 'La Triforce de la Sagesse',
                'optionD' => 'La Triforce du Courage',
                'correctAnswer' => 'B',
                'category' => 'Lore',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Pourquoi Link ne pouvait-il pas manier l’Épée de Légende enfant ?',
                'description' => 'Raison donnée par Rauru.',
                'optionA' => 'Manque de magie',
                'optionB' => 'Pas assez mature',
                'optionC' => 'Épée brisée',
                'optionD' => 'Zelda l’interdisait',
                'correctAnswer' => 'B',
                'category' => 'Lore',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel lieu devient le repaire de Ganondorf après le saut temporel ?',
                'description' => 'Transformation majeure du royaume.',
                'optionA' => 'Temple de l’Ombre',
                'optionB' => 'Château d’Hyrule',
                'optionC' => 'Désert Gerudo',
                'optionD' => 'Montagne de la Mort',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel est désormais l’objectif principal de Link adulte ?',
                'description' => 'Nouvelle mission confiée par Rauru.',
                'optionA' => 'Trouver Zelda',
                'optionB' => 'Éveiller les Sages',
                'optionC' => 'Récolter des Pierres',
                'optionD' => 'Sauver Kokiri',
                'correctAnswer' => 'B',
                'category' => 'Progression',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Combien de Sages doivent être éveillés pour vaincre Ganondorf ?',
                'description' => 'Nombre total dans Ocarina of Time.',
                'optionA' => '3',
                'optionB' => '5',
                'optionC' => '6',
                'optionD' => '7',
                'correctAnswer' => 'C',
                'category' => 'Lore',
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