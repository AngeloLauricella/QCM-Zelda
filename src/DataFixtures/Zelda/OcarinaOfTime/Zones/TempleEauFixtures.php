<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TempleEauFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Temple de l\'Eau']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Temple de l\'Eau');
            $zone->setDescription('Manipulez les niveaux d’eau et sauvez Ruto.');
            $zone->setDisplayOrder(9);
            $zone->setMinPointsToUnlock(850);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Quel est l’état du Lac Hylia en arrivant adulte ?',
                'description' => 'Conséquence du règne de Ganondorf.',
                'optionA' => 'Gelé',
                'optionB' => 'Asséché',
                'optionC' => 'En feu',
                'optionD' => 'Intact',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quelle mélodie permet d’entrer dans le Temple de l’Eau ?',
                'description' => 'Apprise auprès de Sheik.',
                'optionA' => 'Boléro du Feu',
                'optionB' => 'Sérénade de l’Eau',
                'optionC' => 'Requiem de l’Esprit',
                'optionD' => 'Nocturne de l’Ombre',
                'correctAnswer' => 'B',
                'category' => 'Mélodies',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel objet clé est obtenu dans le temple ?',
                'description' => 'Permet de marcher sous l’eau.',
                'optionA' => 'Tunique Zora',
                'optionB' => 'Bottes de Plomb',
                'optionC' => 'Bouclier miroir',
                'optionD' => 'Marteau des Titans',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quelle mécanique centrale définit ce temple ?',
                'description' => 'Source de sa difficulté légendaire.',
                'optionA' => 'Téléportation',
                'optionB' => 'Manipulation du temps',
                'optionC' => 'Changement du niveau d’eau',
                'optionD' => 'Illusions',
                'correctAnswer' => 'C',
                'category' => 'Donjon',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel mini-boss affronte-t-on dans une salle sombre avec un arbre ?',
                'description' => 'Combat miroir iconique.',
                'optionA' => 'Stalfos',
                'optionB' => 'Dark Link',
                'optionC' => 'Iron Knuckle',
                'optionD' => 'Phantom Ganon',
                'correctAnswer' => 'B',
                'category' => 'Mini-boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Quel est le boss final du Temple de l’Eau ?',
                'description' => 'Créature aquatique manipulant l’eau.',
                'optionA' => 'Morpha',
                'optionB' => 'Volvagia',
                'optionC' => 'Bongo Bongo',
                'optionD' => 'Twinrova',
                'correctAnswer' => 'A',
                'category' => 'Boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Comment Morpha attaque-t-il principalement ?',
                'description' => 'Mécanique du combat.',
                'optionA' => 'Il invoque des éclairs',
                'optionB' => 'Il contrôle des tentacules d’eau',
                'optionC' => 'Il se téléporte',
                'optionD' => 'Il crache du feu',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel Sage est éveillé après la victoire ?',
                'description' => 'Princesse Zora.',
                'optionA' => 'Impa',
                'optionB' => 'Nabooru',
                'optionC' => 'Ruto',
                'optionD' => 'Saria',
                'correctAnswer' => 'C',
                'category' => 'Sages',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel médaillon Link reçoit-il ?',
                'description' => 'Symbole du Sage de l’Eau.',
                'optionA' => 'Médaillon du Feu',
                'optionB' => 'Médaillon de l’Eau',
                'optionC' => 'Médaillon de l’Ombre',
                'optionD' => 'Médaillon de l’Esprit',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quelle est la prochaine destination logique après ce temple ?',
                'description' => 'Suite de l’éveil des Sages.',
                'optionA' => 'Temple de l’Ombre',
                'optionB' => 'Temple de l’Esprit',
                'optionC' => 'Château d’Hyrule',
                'optionD' => 'Temple de la Lumière',
                'correctAnswer' => 'A',
                'category' => 'Progression',
                'pointsValue' => 15,
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