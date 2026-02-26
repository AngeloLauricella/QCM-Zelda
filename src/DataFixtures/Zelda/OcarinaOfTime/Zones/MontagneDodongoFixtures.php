<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MontagneDodongoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Créer ou récupérer la zone Montagne Dodongo ---
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Montagne Dodongo']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Montagne Dodongo');
            $zone->setDescription('Grimpez la Montagne Dodongo, affrontez les créatures et sauvez les Gorons.');
            $zone->setDisplayOrder(4);
            $zone->setMinPointsToUnlock(250);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        // --- Questions de la Montagne Dodongo ---
        $questions = [
            [
                'title' => 'Qui dirige les Gorons au sommet de la Montagne Dodongo ?',
                'description' => 'Le chef qui a besoin de l’aide de Link.',
                'optionA' => 'Darunia',
                'optionB' => 'Rauru',
                'optionC' => 'Impa',
                'optionD' => 'Ganondorf',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel problème menace les Gorons ?',
                'description' => 'Un danger que Link doit résoudre pour les aider.',
                'optionA' => 'Invasion de Moblins',
                'optionB' => 'Éruption volcanique et Dodongos',
                'optionC' => 'Malédiction de Ganondorf',
                'optionD' => 'Disparition de l’Ocarina',
                'correctAnswer' => 'B',
                'category' => 'Événements',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel objet crucial Link doit-il apporter au volcan ?',
                'description' => 'Utilisé pour calmer les Dodongos.',
                'optionA' => 'Bombes',
                'optionB' => 'Flèches de feu',
                'optionC' => 'Bouclier Hylia',
                'optionD' => 'Épée Kokiri',
                'correctAnswer' => 'A',
                'category' => 'Objets',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Comment Link neutralise-t-il le Dodongo géant ?',
                'description' => 'Stratégie pour vaincre le boss de la montagne.',
                'optionA' => 'Avec des flèches de feu',
                'optionB' => 'En utilisant des bombes dans sa gueule',
                'optionC' => 'Avec l’Ocarina du Temps',
                'optionD' => 'En frappant sa queue avec l’épée',
                'correctAnswer' => 'B',
                'category' => 'Ennemis',
                'pointsValue' => 20,
            ],
            [
                'title' => 'Quel médaillon Link obtient-il après avoir sauvé les Gorons ?',
                'description' => 'Une des six clés pour affronter Ganondorf.',
                'optionA' => 'Médaillon de l’Eau',
                'optionB' => 'Médaillon de la Feu',
                'optionC' => 'Médaillon de la Terre',
                'optionD' => 'Médaillon du Vent',
                'correctAnswer' => 'C',
                'category' => 'Objets',
                'pointsValue' => 20,
            ],
            [
                'title' => 'Quel Goron accompagne Link dans la montagne pour l’aider ?',
                'description' => 'Un allié clé durant cette aventure.',
                'optionA' => 'Darunia',
                'optionB' => 'Ruto',
                'optionC' => 'Saria',
                'optionD' => 'Nabooru',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel objet récupéré dans la montagne est nécessaire pour progresser dans le donjon ?',
                'description' => 'Objet clé pour le Temple de Dodongo.',
                'optionA' => 'Flèches de feu',
                'optionB' => 'Gantelet de force',
                'optionC' => 'Ocarina du Temps',
                'optionD' => 'Carte du volcan',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel secret peut-on découvrir sur le chemin du sommet ?',
                'description' => 'Petit bonus ou indice pour le joueur attentif.',
                'optionA' => 'Caverne secrète avec coeurs',
                'optionB' => 'Cachette de flèches',
                'optionC' => 'Mini-jeu avec Skulltulas',
                'optionD' => 'Ocarina cachée',
                'correctAnswer' => 'A',
                'category' => 'Secrets',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel est le rôle de Darunia après que Link a vaincu le Dodongo ?',
                'description' => 'Son action impacte la suite de l’histoire.',
                'optionA' => 'Il devient un sage',
                'optionB' => 'Il quitte le royaume',
                'optionC' => 'Il retourne à Kakariko',
                'optionD' => 'Il protège Zelda',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel danger naturel rend l’ascension de la montagne périlleuse ?',
                'description' => 'Élément environnemental que Link doit surmonter.',
                'optionA' => 'Tempêtes de sable',
                'optionB' => 'Éruption volcanique et lave',
                'optionC' => 'Chutes de rochers',
                'optionD' => 'Lac gelé',
                'correctAnswer' => 'B',
                'category' => 'Événements',
                'pointsValue' => 10,
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