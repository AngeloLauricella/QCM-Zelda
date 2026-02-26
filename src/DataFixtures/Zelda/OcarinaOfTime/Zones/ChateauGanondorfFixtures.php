<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ChateauGanondorfFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Château de Ganondorf']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Château de Ganondorf');
            $zone->setDescription('Affrontez Ganondorf et sauvez Hyrule.');
            $zone->setDisplayOrder(12);
            $zone->setMinPointsToUnlock(1300);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Que doivent détruire les Sages avant l’entrée du château ?',
                'description' => 'Protection magique finale.',
                'optionA' => 'Un dragon',
                'optionB' => 'Six barrières élémentaires',
                'optionC' => 'Une armée Gerudo',
                'optionD' => 'La Triforce',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Combien de barrières doit-on traverser dans le château ?',
                'description' => 'Rappel des temples précédents.',
                'optionA' => '3',
                'optionB' => '4',
                'optionC' => '6',
                'optionD' => '7',
                'correctAnswer' => 'C',
                'category' => 'Donjon',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quelle est la première forme du boss final ?',
                'description' => 'Combat magique dans la tour.',
                'optionA' => 'Ganon',
                'optionB' => 'Ganondorf',
                'optionC' => 'Dark Link',
                'optionD' => 'Twinrova',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Comment vaincre Ganondorf dans la première phase ?',
                'description' => 'Mécanique similaire à Phantom Ganon.',
                'optionA' => 'Avec le marteau',
                'optionB' => 'En renvoyant ses boules d’énergie',
                'optionC' => 'Avec les bombes',
                'optionD' => 'Avec la Lentille de Vérité',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 30,
            ],

            [
                'title' => 'Que se passe-t-il après la première victoire ?',
                'description' => 'Événement dramatique.',
                'optionA' => 'Ganondorf s’enfuit',
                'optionB' => 'Le château s’effondre',
                'optionC' => 'Les Sages disparaissent',
                'optionD' => 'Link perd son épée',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Quelle est la forme finale du boss ?',
                'description' => 'Bête démoniaque gigantesque.',
                'optionA' => 'Volvagia',
                'optionB' => 'Morpha',
                'optionC' => 'Ganon',
                'optionD' => 'Bongo Bongo',
                'correctAnswer' => 'C',
                'category' => 'Boss',
                'pointsValue' => 35,
            ],

            [
                'title' => 'Quel objet est temporairement perdu durant le combat final ?',
                'description' => 'Moment de tension.',
                'optionA' => 'Arc',
                'optionB' => 'Épée de Légende',
                'optionC' => 'Bouclier Miroir',
                'optionD' => 'Marteau des Titans',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Qui aide Link à immobiliser Ganon ?',
                'description' => 'Intervention décisive.',
                'optionA' => 'Saria',
                'optionB' => 'Darunia',
                'optionC' => 'Zelda',
                'optionD' => 'Impa',
                'correctAnswer' => 'C',
                'category' => 'Histoire',
                'pointsValue' => 25,
            ],

            [
                'title' => 'Comment Ganon est-il définitivement vaincu ?',
                'description' => 'Coup final.',
                'optionA' => 'Avec des bombes',
                'optionB' => 'Avec le marteau',
                'optionC' => 'Avec l’Épée de Légende',
                'optionD' => 'Avec le Bouclier Miroir',
                'correctAnswer' => 'C',
                'category' => 'Boss',
                'pointsValue' => 35,
            ],

            [
                'title' => 'Quelle est la conclusion de l’histoire ?',
                'description' => 'Fin de la boucle temporelle.',
                'optionA' => 'Link reste adulte',
                'optionB' => 'Hyrule est détruit',
                'optionC' => 'Zelda renvoie Link dans son enfance',
                'optionD' => 'Ganondorf est libéré',
                'correctAnswer' => 'C',
                'category' => 'Épilogue',
                'pointsValue' => 30,
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