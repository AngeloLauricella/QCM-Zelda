<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TempleOmbreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Temple de l\'Ombre']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Temple de l\'Ombre');
            $zone->setDescription('Affrontez les ténèbres et révélez les illusions du temple.');
            $zone->setDisplayOrder(10);
            $zone->setMinPointsToUnlock(1000);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Quel événement se produit à Kakariko avant d’entrer dans le temple ?',
                'description' => 'Déclencheur de l’accès au temple.',
                'optionA' => 'Une inondation',
                'optionB' => 'Une attaque invisible',
                'optionC' => 'Une éruption volcanique',
                'optionD' => 'Une invasion Gerudo',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quelle mélodie permet d’accéder au Temple de l’Ombre ?',
                'description' => 'Enseignée par Sheik.',
                'optionA' => 'Requiem de l’Esprit',
                'optionB' => 'Sérénade de l’Eau',
                'optionC' => 'Nocturne de l’Ombre',
                'optionD' => 'Boléro du Feu',
                'correctAnswer' => 'C',
                'category' => 'Mélodies',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel objet est indispensable pour voir les illusions ?',
                'description' => 'Trouvé dans le Puits.',
                'optionA' => 'Bottes de Plomb',
                'optionB' => 'Lentille de Vérité',
                'optionC' => 'Bouclier miroir',
                'optionD' => 'Marteau des Titans',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel est le thème principal du Temple de l’Ombre ?',
                'description' => 'Atmosphère du donjon.',
                'optionA' => 'Illusions et torture',
                'optionB' => 'Glace et eau',
                'optionC' => 'Feu et lave',
                'optionD' => 'Forêt enchantée',
                'correctAnswer' => 'A',
                'category' => 'Donjon',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel type d’ennemi apparaît fréquemment dans ce temple ?',
                'description' => 'Zombie paralysant.',
                'optionA' => 'Stalfos',
                'optionB' => 'ReDead',
                'optionC' => 'Lizalfos',
                'optionD' => 'Iron Knuckle',
                'correctAnswer' => 'B',
                'category' => 'Ennemis',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel est le boss final du Temple de l’Ombre ?',
                'description' => 'Créature aux mains gigantesques.',
                'optionA' => 'Morpha',
                'optionB' => 'Volvagia',
                'optionC' => 'Bongo Bongo',
                'optionD' => 'Twinrova',
                'correctAnswer' => 'C',
                'category' => 'Boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Quelle est la particularité du combat contre Bongo Bongo ?',
                'description' => 'Mécanique centrale du combat.',
                'optionA' => 'Il vole dans les airs',
                'optionB' => 'Il faut viser ses mains puis son œil',
                'optionC' => 'Il se divise en clones',
                'optionD' => 'Il manipule le feu',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel Sage est éveillé après la victoire ?',
                'description' => 'Protectrice de Zelda.',
                'optionA' => 'Impa',
                'optionB' => 'Nabooru',
                'optionC' => 'Ruto',
                'optionD' => 'Saria',
                'correctAnswer' => 'A',
                'category' => 'Sages',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel médaillon Link reçoit-il ?',
                'description' => 'Symbole du Sage de l’Ombre.',
                'optionA' => 'Médaillon du Feu',
                'optionB' => 'Médaillon de la Lumière',
                'optionC' => 'Médaillon de l’Ombre',
                'optionD' => 'Médaillon de l’Eau',
                'correctAnswer' => 'C',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quelle est la prochaine destination après ce temple ?',
                'description' => 'Dernier temple adulte avant la fin.',
                'optionA' => 'Temple de l’Esprit',
                'optionB' => 'Temple de la Lumière',
                'optionC' => 'Château d’Hyrule',
                'optionD' => 'Temple du Temps',
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