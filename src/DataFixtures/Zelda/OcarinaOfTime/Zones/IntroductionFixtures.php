<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class IntroductionFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        // --- Créer ou récupérer la zone Introduction ---
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Introduction']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Introduction');
            $zone->setDescription('Apprenez les bases de votre aventure avant d’explorer la Forêt Perdue.');
            $zone->setDisplayOrder(0);
            $zone->setMinPointsToUnlock(0);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush(); // ID disponible
        }

        // --- Questions d’introduction ---
        $questions = [
            [
                'title' => 'Qui est le héros de cette aventure ?',
                'description' => 'Le personnage principal que vous incarnez.',
                'optionA' => 'Link',
                'optionB' => 'Zelda',
                'optionC' => 'Ganondorf',
                'optionD' => 'Saria',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Qui est votre amie d’enfance dans le village ?',
                'description' => 'Elle vous guidera dans vos premiers pas.',
                'optionA' => 'Ruto',
                'optionB' => 'Impa',
                'optionC' => 'Saria',
                'optionD' => 'Darunia',
                'correctAnswer' => 'C',
                'category' => 'Personnages',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Quel est votre premier objectif ?',
                'description' => 'Avant de partir en mission, vous devez...',
                'optionA' => 'Explorer le désert',
                'optionB' => 'Récupérer votre premier objet',
                'optionC' => 'Combattre le boss final',
                'optionD' => 'Trouver Ganondorf',
                'correctAnswer' => 'B',
                'category' => 'Quêtes',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Quel objet vous aide à résoudre les énigmes ?',
                'description' => 'Un outil indispensable pour progresser.',
                'optionA' => 'Épée',
                'optionB' => 'Ocarina du Temps',
                'optionC' => 'Bottes',
                'optionD' => 'Bouclier',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Comment vous déplacez-vous rapidement ?',
                'description' => 'Pour traverser la zone sans perdre de temps.',
                'optionA' => 'Courir',
                'optionB' => 'Sauter',
                'optionC' => 'Nager',
                'optionD' => 'Voler',
                'correctAnswer' => 'A',
                'category' => 'Mécaniques',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Qui vous guide au début de l’aventure ?',
                'description' => 'Un petit être magique vous accompagne.',
                'optionA' => 'Navi',
                'optionB' => 'Saria',
                'optionC' => 'Ruto',
                'optionD' => 'Impa',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Où commence votre aventure ?',
                'description' => 'Le village ou la zone de départ.',
                'optionA' => 'Forêt Perdue',
                'optionB' => 'Château d’Hyrule',
                'optionC' => 'Désert Géant',
                'optionD' => 'Montagne Désolée',
                'correctAnswer' => 'A',
                'category' => 'Zones',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Quel est votre premier ennemi à éviter ou combattre ?',
                'description' => 'Le tout premier monstre rencontré.',
                'optionA' => 'Keese',
                'optionB' => 'Moblin',
                'optionC' => 'Stalfos',
                'optionD' => 'Deku Baba',
                'correctAnswer' => 'D',
                'category' => 'Ennemis',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Quel artefact est essentiel pour continuer l’aventure ?',
                'description' => 'Un objet clé du tutoriel.',
                'optionA' => 'Épée de Kokiri',
                'optionB' => 'Ocarina du Temps',
                'optionC' => 'Grappin',
                'optionD' => 'Bouclier Hylien',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Comment apprendre les bases du combat ?',
                'description' => 'Petit tutoriel pour attaquer les ennemis.',
                'optionA' => 'En parlant à Saria',
                'optionB' => 'En combattant un Deku Baba',
                'optionC' => 'En explorant la montagne',
                'optionD' => 'En chantant l’Ocarina',
                'correctAnswer' => 'B',
                'category' => 'Mécaniques',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Comment interagir avec les personnages ?',
                'description' => 'Pour obtenir des conseils et quêtes.',
                'optionA' => 'Cliquer sur eux',
                'optionB' => 'Parler',
                'optionC' => 'Attaquer',
                'optionD' => 'Ignorer',
                'correctAnswer' => 'B',
                'category' => 'Mécaniques',
                'pointsValue' => 5,
            ],
            [
                'title' => 'Quel est le but de l’introduction ?',
                'description' => 'Comprendre les bases avant de partir en mission.',
                'optionA' => 'Se promener',
                'optionB' => 'Apprendre les mécaniques',
                'optionC' => 'Combattre Ganondorf',
                'optionD' => 'Explorer le désert',
                'correctAnswer' => 'B',
                'category' => 'Tutoriel',
                'pointsValue' => 5,
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
            $question->setStep(0); // étape intro
            $manager->persist($question);
        }

        $manager->flush();
    }
}
