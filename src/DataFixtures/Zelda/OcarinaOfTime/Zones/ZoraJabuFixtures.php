<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ZoraJabuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Zone Domaine Zora ---
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Domaine Zora & Jabu-Jabu']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Domaine Zora & Jabu-Jabu');
            $zone->setDescription('Aidez le peuple Zora et explorez le ventre de Jabu-Jabu pour obtenir la dernière Pierre Ancestrale.');
            $zone->setDisplayOrder(5);
            $zone->setMinPointsToUnlock(350);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        $questions = [

            [
                'title' => 'Quel peuple vit au Domaine Zora ?',
                'description' => 'Peuple aquatique allié de la famille royale.',
                'optionA' => 'Gorons',
                'optionB' => 'Gerudos',
                'optionC' => 'Zoras',
                'optionD' => 'Sheikahs',
                'correctAnswer' => 'C',
                'category' => 'Peuples',
                'pointsValue' => 10,
            ],

            [
                'title' => 'Qui est le dirigeant du Domaine Zora ?',
                'description' => 'Père de la princesse Ruto.',
                'optionA' => 'Rauru',
                'optionB' => 'Roi Zora',
                'optionC' => 'Darunia',
                'optionD' => 'Ganondorf',
                'correctAnswer' => 'B',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Pourquoi Link vient-il au Domaine Zora ?',
                'description' => 'Objectif principal de cette visite.',
                'optionA' => 'Chercher l’Épée de Légende',
                'optionB' => 'Obtenir la Saphir Zora',
                'optionC' => 'Trouver Zelda',
                'optionD' => 'Affronter Ganondorf',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Que doit utiliser Link pour entrer dans Jabu-Jabu ?',
                'description' => 'Objet placé devant la divinité marine.',
                'optionA' => 'Une bombe',
                'optionB' => 'Un poisson dans une bouteille',
                'optionC' => 'L’Ocarina du Temps',
                'optionD' => 'Une flèche de feu',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Qui Link rencontre-t-il à l’intérieur de Jabu-Jabu ?',
                'description' => 'Personnage important et futur Sage.',
                'optionA' => 'Saria',
                'optionB' => 'Nabooru',
                'optionC' => 'Ruto',
                'optionD' => 'Malon',
                'correctAnswer' => 'C',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel objet important Link obtient-il dans le donjon ?',
                'description' => 'Permet de viser et tirer à distance.',
                'optionA' => 'Arc des Fées',
                'optionB' => 'Boomerang',
                'optionC' => 'Grappin',
                'optionD' => 'Marteau',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Quel est le boss du ventre de Jabu-Jabu ?',
                'description' => 'Créature parasitaire électrique.',
                'optionA' => 'King Dodongo',
                'optionB' => 'Barinade',
                'optionC' => 'Gohma',
                'optionD' => 'Volvagia',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quelle Pierre Ancestrale Link obtient-il après avoir vaincu Barinade ?',
                'description' => 'Dernière pierre nécessaire pour ouvrir la Porte du Temps.',
                'optionA' => 'Émeraude Kokiri',
                'optionB' => 'Rubis Goron',
                'optionC' => 'Saphir Zora',
                'optionD' => 'Cristal de Lumière',
                'correctAnswer' => 'C',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],

            [
                'title' => 'Quel lien spécial Ruto prétend-elle avoir avec Link ?',
                'description' => 'Déclaration surprenante à la fin du donjon.',
                'optionA' => 'Il est son garde',
                'optionB' => 'Il est son fiancé',
                'optionC' => 'Il est son frère',
                'optionD' => 'Il est son rival',
                'correctAnswer' => 'B',
                'category' => 'Histoire',
                'pointsValue' => 15,
            ],

            [
                'title' => 'Que peut désormais faire Link après avoir obtenu les trois Pierres Ancestrales ?',
                'description' => 'Prochaine grande étape de l’histoire.',
                'optionA' => 'Entrer dans le Temple du Temps',
                'optionB' => 'Accéder au Désert Gerudo',
                'optionC' => 'Combattre Ganondorf',
                'optionD' => 'Obtenir l’Arc des Fées',
                'correctAnswer' => 'A',
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