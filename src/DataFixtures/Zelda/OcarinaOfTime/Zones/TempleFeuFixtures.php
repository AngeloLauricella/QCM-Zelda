<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TempleFeuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Temple du Feu']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Temple du Feu');
            $zone->setDescription('Libérez les Gorons et vainquez le dragon Volvagia.');
            $zone->setDisplayOrder(8);
            $zone->setMinPointsToUnlock(700);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Que découvre Link en arrivant à Goron City adulte ?',
                'description' => 'Situation des Gorons.',
                'optionA' => 'Ils ont fui',
                'optionB' => 'Ils sont transformés en pierre',
                'optionC' => 'Ils sont emprisonnés',
                'optionD' => 'Ils combattent Ganondorf',
                'correctAnswer' => 'C',
                'category' => 'Histoire',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel objet permet d’ouvrir l’entrée du Temple du Feu ?',
                'description' => 'Objet lié à Darunia.',
                'optionA' => 'Chant du Soleil',
                'optionB' => 'Tunique Goron',
                'optionC' => 'Boléro du Feu',
                'optionD' => 'Mélodie de la Forêt',
                'correctAnswer' => 'C',
                'category' => 'Mélodies',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel équipement est indispensable pour survivre dans le temple ?',
                'description' => 'Protection contre la chaleur.',
                'optionA' => 'Bottes de plomb',
                'optionB' => 'Tunique Goron',
                'optionC' => 'Bouclier miroir',
                'optionD' => 'Capuche Kokiri',
                'correctAnswer' => 'B',
                'category' => 'Équipement',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel objet majeur obtient-on dans le Temple du Feu ?',
                'description' => 'Utilisé pour briser des blocs rouges.',
                'optionA' => 'Arc',
                'optionB' => 'Grappin',
                'optionC' => 'Marteau des Titans',
                'optionD' => 'Boomerang',
                'correctAnswer' => 'C',
                'category' => 'Objets',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel ennemi mini-boss affronte-t-on dans le temple ?',
                'description' => 'Guerrier massif en armure.',
                'optionA' => 'Stalfos',
                'optionB' => 'Iron Knuckle',
                'optionC' => 'Lizalfos',
                'optionD' => 'ReDead',
                'correctAnswer' => 'B',
                'category' => 'Ennemis',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel est le boss du Temple du Feu ?',
                'description' => 'Dragon antique ressuscité.',
                'optionA' => 'Morpha',
                'optionB' => 'Bongo Bongo',
                'optionC' => 'Volvagia',
                'optionD' => 'Twinrova',
                'correctAnswer' => 'C',
                'category' => 'Boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Comment vaincre Volvagia efficacement ?',
                'description' => 'Mécanique clé du combat.',
                'optionA' => 'Avec l’arc',
                'optionB' => 'Avec le marteau lorsqu’il sort du sol',
                'optionC' => 'Avec le grappin',
                'optionD' => 'Avec le boomerang',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel Sage est éveillé après la victoire ?',
                'description' => 'Chef des Gorons.',
                'optionA' => 'Rauru',
                'optionB' => 'Darunia',
                'optionC' => 'Impa',
                'optionD' => 'Nabooru',
                'correctAnswer' => 'B',
                'category' => 'Sages',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel médaillon Link reçoit-il ?',
                'description' => 'Symbole du Sage du Feu.',
                'optionA' => 'Médaillon du Feu',
                'optionB' => 'Médaillon de la Forêt',
                'optionC' => 'Médaillon de l’Eau',
                'optionD' => 'Médaillon de la Lumière',
                'correctAnswer' => 'A',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel est l’objectif après le Temple du Feu ?',
                'description' => 'Prochaine destination logique.',
                'optionA' => 'Temple de l’Eau',
                'optionB' => 'Temple de l’Ombre',
                'optionC' => 'Temple de l’Esprit',
                'optionD' => 'Retour au Château',
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