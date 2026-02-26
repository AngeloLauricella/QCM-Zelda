<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class KakarikoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Créer ou récupérer la zone Village de Kakariko ---
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Village de Kakariko']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Village de Kakariko');
            $zone->setDescription('Explorez le village paisible de Kakariko et découvrez ses habitants et secrets.');
            $zone->setDisplayOrder(3);
            $zone->setMinPointsToUnlock(200);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush();
        }

        // --- Questions du Village de Kakariko ---
        $questions = [
            [
                'title' => 'Qui est le chef du Village de Kakariko ?',
                'description' => 'Le personnage qui veille sur le village.',
                'optionA' => 'Impa',
                'optionB' => 'Anju',
                'optionC' => 'Dampe',
                'optionD' => 'Malon',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel personnage enseigne à Link des chansons importantes ?',
                'description' => 'Celui qui lui transmet des mélodies magiques.',
                'optionA' => 'Saria',
                'optionB' => 'Songbird',
                'optionC' => 'Malon',
                'optionD' => 'Impa',
                'correctAnswer' => 'C',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel cimetière célèbre se trouve près du village ?',
                'description' => 'Où Dampe le tombeur de morts officie.',
                'optionA' => 'Cimetière de Kakariko',
                'optionB' => 'Cimetière d’Hyrule',
                'optionC' => 'Tombe de la Reine',
                'optionD' => 'Cimetière Kokiri',
                'correctAnswer' => 'A',
                'category' => 'Lieux',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Qui est le vieux fossoyeur célèbre du village ?',
                'description' => 'Il offre des mini-jeux et des trésors cachés.',
                'optionA' => 'Darunia',
                'optionB' => 'Dampe',
                'optionC' => 'Rauru',
                'optionD' => 'Ganondorf',
                'correctAnswer' => 'B',
                'category' => 'Personnages',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel objet rare peut-on trouver dans le cimetière de Kakariko ?',
                'description' => 'Récompense importante pour les joueurs curieux.',
                'optionA' => 'Masque de Goron',
                'optionB' => 'Armes Kokiri',
                'optionC' => 'Coeur supplémentaire',
                'optionD' => 'Épée de Maître',
                'correctAnswer' => 'C',
                'category' => 'Objets',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel personnage s’occupe de l’élevage des chevaux et du ranch ?',
                'description' => 'Celui qui entraîne les chevaux pour Link.',
                'optionA' => 'Malon',
                'optionB' => 'Impa',
                'optionC' => 'Saria',
                'optionD' => 'Ruto',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel passage secret relie Kakariko au Château d’Hyrule ?',
                'description' => 'Un itinéraire caché pour les aventuriers.',
                'optionA' => 'Tunnel sous la colline',
                'optionB' => 'Portail magique',
                'optionC' => 'Porte principale',
                'optionD' => 'Pont du ruisseau',
                'correctAnswer' => 'A',
                'category' => 'Secrets',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quelle créature nocturne hante le cimetière ?',
                'description' => 'Un ennemi que Link doit éviter ou combattre.',
                'optionA' => 'Skulltula',
                'optionB' => 'ReDead',
                'optionC' => 'Darknut',
                'optionD' => 'Moblin',
                'correctAnswer' => 'B',
                'category' => 'Ennemis',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel mini-jeu permet de gagner des objets au village ?',
                'description' => 'Activité optionnelle pour les aventuriers.',
                'optionA' => 'Course de chevaux',
                'optionB' => 'Concours de pêche',
                'optionC' => 'Tombe de Dampe',
                'optionD' => 'Chasse aux Skulltulas',
                'correctAnswer' => 'C',
                'category' => 'Événements',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quelle herbe médicinale est cultivée près du village ?',
                'description' => 'Utilisée pour restaurer la santé de Link.',
                'optionA' => 'Herbe Verte',
                'optionB' => 'Hyancinthe',
                'optionC' => 'Fleur de Lon Lon',
                'optionD' => 'Herbe Mojo',
                'correctAnswer' => 'C',
                'category' => 'Objets',
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