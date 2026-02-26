<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TempleEspritFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Temple de l\'Esprit']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Temple de l\'Esprit');
            $zone->setDescription('Explorez le désert et sauvez Nabooru des sorcières Twinrova.');
            $zone->setDisplayOrder(11);
            $zone->setMinPointsToUnlock(1150);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Quelle mélodie permet d’accéder au désert ?',
                'description' => 'Apprise enfant.',
                'optionA' => 'Boléro du Feu',
                'optionB' => 'Requiem de l’Esprit',
                'optionC' => 'Nocturne de l’Ombre',
                'optionD' => 'Sérénade de l’Eau',
                'correctAnswer' => 'B',
                'category' => 'Mélodies',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quelle est la particularité du Temple de l’Esprit ?',
                'description' => 'Structure unique.',
                'optionA' => 'Uniquement sous-marin',
                'optionB' => 'Accessible seulement adulte',
                'optionC' => 'Parties enfant et adulte',
                'optionD' => 'Temple invisible',
                'correctAnswer' => 'C',
                'category' => 'Donjon',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel objet majeur est obtenu adulte ?',
                'description' => 'Permet de refléter la magie.',
                'optionA' => 'Lentille de Vérité',
                'optionB' => 'Bouclier Miroir',
                'optionC' => 'Marteau des Titans',
                'optionD' => 'Bottes de Plomb',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel ennemi emblématique combat-on dans ce temple ?',
                'description' => 'Guerrier en armure massive.',
                'optionA' => 'Stalfos',
                'optionB' => 'ReDead',
                'optionC' => 'Iron Knuckle',
                'optionD' => 'Lizalfos',
                'correctAnswer' => 'C',
                'category' => 'Mini-boss',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Qui est contrôlée par la magie noire ?',
                'description' => 'Chef des Gerudos.',
                'optionA' => 'Impa',
                'optionB' => 'Ruto',
                'optionC' => 'Saria',
                'optionD' => 'Nabooru',
                'correctAnswer' => 'D',
                'category' => 'Histoire',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel est le boss final du temple ?',
                'description' => 'Deux sorcières jumelles.',
                'optionA' => 'Bongo Bongo',
                'optionB' => 'Twinrova',
                'optionC' => 'Morpha',
                'optionD' => 'Volvagia',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Comment vaincre Twinrova ?',
                'description' => 'Mécanique principale du combat.',
                'optionA' => 'Avec le marteau',
                'optionB' => 'En reflétant leur magie',
                'optionC' => 'Avec les bombes',
                'optionD' => 'Avec la Lentille de Vérité',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Quel Sage est éveillé après la victoire ?',
                'description' => 'Sage du Désert.',
                'optionA' => 'Nabooru',
                'optionB' => 'Impa',
                'optionC' => 'Darunia',
                'optionD' => 'Ruto',
                'correctAnswer' => 'A',
                'category' => 'Sages',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quel médaillon Link reçoit-il ?',
                'description' => 'Symbole du Sage de l’Esprit.',
                'optionA' => 'Médaillon du Feu',
                'optionB' => 'Médaillon de l’Ombre',
                'optionC' => 'Médaillon de l’Esprit',
                'optionD' => 'Médaillon de la Lumière',
                'correctAnswer' => 'C',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel est le dernier objectif après ce temple ?',
                'description' => 'Fin de la quête des Sages.',
                'optionA' => 'Retourner enfant',
                'optionB' => 'Entrer dans le Château de Ganondorf',
                'optionC' => 'Aller au Temple du Temps',
                'optionD' => 'Revoir Sheik',
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