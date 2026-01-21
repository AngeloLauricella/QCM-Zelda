<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Questions Introduction - 7 questions
        $introQuestions = [
            [
                'title' => 'Qui poursuit Link dans la forêt de Kokiri?',
                'description' => 'En début du jeu, une créature poursuit Link. Qui est-ce?',
                'optionA' => 'Saria',
                'optionB' => 'Navi',
                'optionC' => 'La Reine Gohma',
                'optionD' => 'Le Grand Arbre Deku',
                'correctAnswer' => 'B',
                'category' => 'introduction',
                'order' => 1,
            ],
            [
                'title' => 'Quel est le nom du sage qui a été poursuivie à travers les ères?',
                'description' => 'Cherchant un sage qui a traversé les âges',
                'optionA' => 'Zelda',
                'optionB' => 'Impa',
                'optionC' => 'Nabooru',
                'optionD' => 'Dinette',
                'correctAnswer' => 'A',
                'category' => 'introduction',
                'order' => 2,
            ],
            [
                'title' => 'Quel objet permet à Link de voyager dans le temps?',
                'description' => 'Un artefact puissant permet au héros de voyager à travers les âges',
                'optionA' => 'La Triforce',
                'optionB' => 'L\'Épée de la Légende',
                'optionC' => 'L\'Ocarina',
                'optionD' => 'La Boule de Cristal',
                'correctAnswer' => 'C',
                'category' => 'introduction',
                'order' => 3,
            ],
            [
                'title' => 'Dans quelle région Link grandit-il en tant qu\'enfant?',
                'description' => 'Où Link passe-t-il son enfance?',
                'optionA' => 'Le Château d\'Hyrule',
                'optionB' => 'La Forêt de Kokiri',
                'optionC' => 'Le Village d\'Ordon',
                'optionD' => 'La Citadelle',
                'correctAnswer' => 'B',
                'category' => 'introduction',
                'order' => 4,
            ],
            [
                'title' => 'Quel est le peuple qui habite dans les forêts?',
                'description' => 'Les habitants de la forêt profonde',
                'optionA' => 'Les Zoras',
                'optionB' => 'Les Gorons',
                'optionC' => 'Les Kokiis',
                'optionD' => 'Les Gerudo',
                'correctAnswer' => 'C',
                'category' => 'introduction',
                'order' => 5,
            ],
            [
                'title' => 'Quel est le nom de la fée qui accompagne Link?',
                'description' => 'La compagne volante de Link',
                'optionA' => 'Sprite',
                'optionB' => 'Navi',
                'optionC' => 'Tatl',
                'optionD' => 'Tael',
                'correctAnswer' => 'B',
                'category' => 'introduction',
                'order' => 6,
            ],
            [
                'title' => 'Quel est le roi du Royaume d\'Hyrule?',
                'description' => 'Le souverain de ce monde',
                'optionA' => 'Le Roi Léonard',
                'optionB' => 'Le Roi Hyrule',
                'optionC' => 'Le Roi Ganondorf',
                'optionD' => 'Le Roi Zora',
                'correctAnswer' => 'B',
                'category' => 'introduction',
                'order' => 7,
            ],
        ];

        // Questions Forêt - 7 questions
        $foretQuestions = [
            [
                'title' => 'Qui est l\'amie d\'enfance de Link dans la forêt?',
                'description' => 'La meilleure amie de Link dans la forêt de Kokiri',
                'optionA' => 'Saria',
                'optionB' => 'Zelda',
                'optionC' => 'Impa',
                'optionD' => 'Nabooru',
                'correctAnswer' => 'A',
                'category' => 'foret',
                'order' => 1,
            ],
            [
                'title' => 'Quel est le grand arbre de la forêt?',
                'description' => 'L\'ancien arbre qui protège la forêt',
                'optionA' => 'L\'Arbre Mojo',
                'optionB' => 'Le Grand Arbre Deku',
                'optionC' => 'L\'Arbre Sacré',
                'optionD' => 'L\'Arbre de Vie',
                'correctAnswer' => 'B',
                'category' => 'foret',
                'order' => 2,
            ],
            [
                'title' => 'Quel instrument de musique est très important?',
                'description' => 'Un instrument magique capable de créer des portails',
                'optionA' => 'La Flûte',
                'optionB' => 'La Trompette',
                'optionC' => 'L\'Ocarina',
                'optionD' => 'La Harpe',
                'correctAnswer' => 'C',
                'category' => 'foret',
                'order' => 3,
            ],
            [
                'title' => 'Quel est le boss de la forêt de Kokiri?',
                'description' => 'La créature qui hante le donjon de la forêt',
                'optionA' => 'Gohma',
                'optionB' => 'Queen Gohma',
                'optionC' => 'La Reine Gohma',
                'optionD' => 'Spector',
                'correctAnswer' => 'A',
                'category' => 'foret',
                'order' => 4,
            ],
            [
                'title' => 'Quel sort permet à Link de voir dans le noir?',
                'description' => 'Une lumière magique pour explorer',
                'optionA' => 'Feu',
                'optionB' => 'Lumière',
                'optionC' => 'Glace',
                'optionD' => 'Tonnerre',
                'correctAnswer' => 'B',
                'category' => 'foret',
                'order' => 5,
            ],
            [
                'title' => 'Quel peuple vit dans les montagnes?',
                'description' => 'Les habitants rocailleux',
                'optionA' => 'Les Zoras',
                'optionB' => 'Les Gorons',
                'optionC' => 'Les Gerudo',
                'optionD' => 'Les Kokiis',
                'correctAnswer' => 'B',
                'category' => 'foret',
                'order' => 6,
            ],
            [
                'title' => 'Quel est le nom de l\'épée légendaire?',
                'description' => 'L\'arme la plus puissante du monde',
                'optionA' => 'L\'épée de Nayru',
                'optionB' => 'L\'épée de Farore',
                'optionC' => 'L\'épée de la Légende',
                'optionD' => 'L\'épée de Din',
                'correctAnswer' => 'C',
                'category' => 'foret',
                'order' => 7,
            ],
        ];

        // Bonus Liane
        $bonusQuestion = [
            'title' => 'Qu\'est-ce que la Triforce?',
            'description' => 'Un artefact très ancien',
            'optionA' => 'Trois cristaux',
            'optionB' => 'Trois triangles sacrés',
            'optionC' => 'Trois épées',
            'optionD' => 'Trois clés',
            'correctAnswer' => 'B',
            'category' => 'bonus',
            'order' => 1,
        ];

        // Questions Montagne
        $montagneQuestions = [
            [
                'title' => 'Quel est le leader des Gorons?',
                'description' => 'Le chef des Gorons',
                'optionA' => 'Darunia',
                'optionB' => 'Ganondorf',
                'optionC' => 'Volvagia',
                'optionD' => 'Argorok',
                'correctAnswer' => 'A',
                'category' => 'montagne',
                'order' => 1,
            ],
        ];

        // Créer les questions d'introduction
        foreach ($introQuestions as $data) {
            $question = new Question();
            $question->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setOptionA($data['optionA'])
                ->setOptionB($data['optionB'])
                ->setOptionC($data['optionC'])
                ->setOptionD($data['optionD'])
                ->setCorrectAnswer($data['correctAnswer'])
                ->setCategory($data['category'])
                ->setDisplayOrder($data['order'])
                ->setPointsValue(3);
            $manager->persist($question);
        }

        // Créer les questions de forêt
        foreach ($foretQuestions as $data) {
            $question = new Question();
            $question->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setOptionA($data['optionA'])
                ->setOptionB($data['optionB'])
                ->setOptionC($data['optionC'])
                ->setOptionD($data['optionD'])
                ->setCorrectAnswer($data['correctAnswer'])
                ->setCategory($data['category'])
                ->setDisplayOrder($data['order'])
                ->setPointsValue(3);
            $manager->persist($question);
        }

        // Créer la question bonus
        $bonusQ = new Question();
        $bonusQ->setTitle($bonusQuestion['title'])
            ->setDescription($bonusQuestion['description'])
            ->setOptionA($bonusQuestion['optionA'])
            ->setOptionB($bonusQuestion['optionB'])
            ->setOptionC($bonusQuestion['optionC'])
            ->setOptionD($bonusQuestion['optionD'])
            ->setCorrectAnswer($bonusQuestion['correctAnswer'])
            ->setCategory($bonusQuestion['category'])
            ->setDisplayOrder($bonusQuestion['order'])
            ->setPointsValue(5);
        $manager->persist($bonusQ);

        // Créer les questions montagne
        foreach ($montagneQuestions as $data) {
            $question = new Question();
            $question->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setOptionA($data['optionA'])
                ->setOptionB($data['optionB'])
                ->setOptionC($data['optionC'])
                ->setOptionD($data['optionD'])
                ->setCorrectAnswer($data['correctAnswer'])
                ->setCategory($data['category'])
                ->setDisplayOrder($data['order'])
                ->setPointsValue(3);
            $manager->persist($question);
        }

        $manager->flush();
    }
}
