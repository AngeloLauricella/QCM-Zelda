<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $questionsByCategory = [

            'introduction' => [
                [
                    'title' => 'Qui poursuit Link dans la forêt de Kokiri?',
                    'description' => 'En début du jeu, une créature poursuit Link. Qui est-ce?',
                    'optionA' => 'Saria',
                    'optionB' => 'Navi',
                    'optionC' => 'La Reine Gohma',
                    'optionD' => 'Le Grand Arbre Deku',
                    'correctAnswer' => 'B',
                    'order' => 1,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel est le nom de la ville natale de Link?',
                    'description' => 'La ville où Link a grandi',
                    'optionA' => 'Kakariko',
                    'optionB' => 'Hyrule Castle Town',
                    'optionC' => 'Ordon Village',
                    'optionD' => 'Kokiri Forest',
                    'correctAnswer' => 'D',
                    'order' => 2,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel est le nom de l’arbre géant qui protège la forêt Kokiri?',
                    'description' => 'L’arbre sacré au centre de la forêt',
                    'optionA' => 'Le Grand Arbre Deku',
                    'optionB' => 'L’Arbre Mojo',
                    'optionC' => 'L’Arbre de la Vie',
                    'optionD' => 'L’Arbre des Esprits',
                    'correctAnswer' => 'A',
                    'order' => 3,
                    'points_value' => 3
                ],
                [
                    'title' => 'Qui réveille Link au début du jeu?',
                    'description' => 'Au tout début de l’aventure, qui vient réveiller Link?',
                    'optionA' => 'Zelda',
                    'optionB' => 'Saria',
                    'optionC' => 'Navi',
                    'optionD' => 'Impa',
                    'correctAnswer' => 'C',
                    'category' => 'introduction',
                    'order' => 1,
                    'points_value' => 3
                ],
                [
                    'title' => 'Dans quel village Link grandit-il?',
                    'description' => 'Le lieu où Link passe son enfance.',
                    'optionA' => 'Village Cocorico',
                    'optionB' => 'Forêt de Kokiri',
                    'optionC' => 'Hyrule',
                    'optionD' => 'Citadelle',
                    'correctAnswer' => 'B',
                    'category' => 'introduction',
                    'order' => 2,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel objet Link doit obtenir avant d’entrer dans l’Arbre Mojo?',
                    'description' => 'Un équipement essentiel pour se défendre.',
                    'optionA' => 'Épée Kokiri',
                    'optionB' => 'Arc',
                    'optionC' => 'Bouclier Hylien',
                    'optionD' => 'Bâton Mojo',
                    'correctAnswer' => 'A',
                    'category' => 'introduction',
                    'order' => 3,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel personnage donne l’Ocarina des Fées à Link?',
                    'description' => 'Un cadeau important reçu avant de quitter la forêt.',
                    'optionA' => 'Zelda',
                    'optionB' => 'Navi',
                    'optionC' => 'Saria',
                    'optionD' => 'Impa',
                    'correctAnswer' => 'C',
                    'category' => 'introduction',
                    'order' => 4,
                    'points_value' => 3
                ],
                [
                    'title' => 'Qui protège la forêt Kokiri?',
                    'description' => 'L’esprit ancien gardien de la forêt.',
                    'optionA' => 'Ganondorf',
                    'optionB' => 'Le Grand Arbre Deku',
                    'optionC' => 'Darunia',
                    'optionD' => 'Rauru',
                    'correctAnswer' => 'B',
                    'category' => 'introduction',
                    'order' => 5,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quelle pierre spirituelle est obtenue après le premier donjon?',
                    'description' => 'Récompense donnée par l’Arbre Mojo.',
                    'optionA' => 'Rubis Goron',
                    'optionB' => 'Saphir Zora',
                    'optionC' => 'Émeraude Kokiri',
                    'optionD' => 'Pierre du Temps',
                    'correctAnswer' => 'C',
                    'category' => 'introduction',
                    'order' => 6,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel est le nom de la princesse d’Hyrule?',
                    'description' => 'La princesse que Link rencontre au château.',
                    'optionA' => 'Malon',
                    'optionB' => 'Zelda',
                    'optionC' => 'Saria',
                    'optionD' => 'Nabooru',
                    'correctAnswer' => 'B',
                    'category' => 'introduction',
                    'order' => 7,
                    'points_value' => 3
                ],
                [
                    'title' => 'Qui accompagne Zelda en tant que garde du corps?',
                    'description' => 'Une fidèle protectrice.',
                    'optionA' => 'Impa',
                    'optionB' => 'Navi',
                    'optionC' => 'Saria',
                    'optionD' => 'Ruto',
                    'correctAnswer' => 'A',
                    'category' => 'introduction',
                    'order' => 8,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel antagoniste Link aperçoit pour la première fois au château?',
                    'description' => 'Un personnage mystérieux prêtant allégeance au roi.',
                    'optionA' => 'Dark Link',
                    'optionB' => 'Ganondorf',
                    'optionC' => 'Volvagia',
                    'optionD' => 'Bongo Bongo',
                    'correctAnswer' => 'B',
                    'category' => 'introduction',
                    'order' => 9,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel animal aide Link à entrer dans le château d’Hyrule?',
                    'description' => 'Un moyen inattendu pour accéder au jardin.',
                    'optionA' => 'Un cheval',
                    'optionB' => 'Un chien',
                    'optionC' => 'Un poulet',
                    'optionD' => 'Un renard',
                    'correctAnswer' => 'C',
                    'category' => 'introduction',
                    'order' => 10,
                    'points_value' => 3
                ],

            ],

            'foret' => [
                [
                    'title' => 'Qui est l\'amie d\'enfance de Link dans la forêt?',
                    'description' => 'La meilleure amie de Link dans la forêt de Kokiri',
                    'optionA' => 'Saria',
                    'optionB' => 'Zelda',
                    'optionC' => 'Impa',
                    'optionD' => 'Nabooru',
                    'correctAnswer' => 'A',
                    'order' => 1,
                    'points_value' => 3
                ],
                [
                    'title' => 'Comment s’appelle la zone labyrinthique reliant plusieurs régions de la forêt?',
                    'description' => 'Un endroit où l’on peut facilement se perdre.',
                    'optionA' => 'Bois Interdits',
                    'optionB' => 'Bois Perdus',
                    'optionC' => 'Forêt Sacrée',
                    'optionD' => 'Jungle Kokiri',
                    'correctAnswer' => 'B',
                    'category' => 'foret',
                    'order' => 1,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel personnage apprend à Link le Chant de Saria?',
                    'description' => 'Une amie d’enfance vivant dans la forêt.',
                    'optionA' => 'Zelda',
                    'optionB' => 'Malon',
                    'optionC' => 'Saria',
                    'optionD' => 'Impa',
                    'correctAnswer' => 'C',
                    'category' => 'foret',
                    'order' => 2,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel lieu secret se trouve au fond des Bois Perdus?',
                    'description' => 'Une clairière protégée par des ennemis.',
                    'optionA' => 'Bosquet Sacré',
                    'optionB' => 'Temple du Temps',
                    'optionC' => 'Mont du Péril',
                    'optionD' => 'Lac Hylia',
                    'correctAnswer' => 'A',
                    'category' => 'foret',
                    'order' => 3,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel ennemi garde l’entrée du Bosquet Sacré lorsque Link est adulte?',
                    'description' => 'Un adversaire imposant armé d’une lance.',
                    'optionA' => 'Moblin',
                    'optionB' => 'Stalfos',
                    'optionC' => 'Lizalfos',
                    'optionD' => 'Dark Link',
                    'correctAnswer' => 'A',
                    'category' => 'foret',
                    'order' => 4,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel temple se situe dans le Bosquet Sacré?',
                    'description' => 'Un ancien sanctuaire envahi par des créatures.',
                    'optionA' => 'Temple de l’Esprit',
                    'optionB' => 'Temple de la Forêt',
                    'optionC' => 'Temple de l’Ombre',
                    'optionD' => 'Temple du Feu',
                    'correctAnswer' => 'B',
                    'category' => 'foret',
                    'order' => 5,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel objet Link obtient dans le Temple de la Forêt?',
                    'description' => 'Un outil permettant de s’accrocher à des cibles lointaines.',
                    'optionA' => 'Arc des Fées',
                    'optionB' => 'Masse des Titans',
                    'optionC' => 'Grappin',
                    'optionD' => 'Bottes de plomb',
                    'correctAnswer' => 'C',
                    'category' => 'foret',
                    'order' => 6,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel boss Link affronte à la fin du Temple de la Forêt?',
                    'description' => 'Une incarnation fantomatique du roi du désert.',
                    'optionA' => 'Volvagia',
                    'optionB' => 'Phantom Ganon',
                    'optionC' => 'Morpha',
                    'optionD' => 'Bongo Bongo',
                    'correctAnswer' => 'B',
                    'category' => 'foret',
                    'order' => 7,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel Sage est réveillé après la purification du Temple de la Forêt?',
                    'description' => 'Une amie de Link devenue Sage.',
                    'optionA' => 'Ruto',
                    'optionB' => 'Nabooru',
                    'optionC' => 'Saria',
                    'optionD' => 'Impa',
                    'correctAnswer' => 'C',
                    'category' => 'foret',
                    'order' => 8,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel médaillon Link reçoit après avoir terminé le Temple de la Forêt?',
                    'description' => 'Symbole du pouvoir du Sage de la Forêt.',
                    'optionA' => 'Médaillon de la Lumière',
                    'optionB' => 'Médaillon de la Forêt',
                    'optionC' => 'Médaillon du Feu',
                    'optionD' => 'Médaillon de l’Eau',
                    'correctAnswer' => 'B',
                    'category' => 'foret',
                    'order' => 9,
                    'points_value' => 3
                ],
                [
                    'title' => 'Quel chant permet à Link de se téléporter près du Temple de la Forêt?',
                    'description' => 'Appris après avoir éveillé le Sage.',
                    'optionA' => 'Boléro du Feu',
                    'optionB' => 'Nocturne de l’Ombre',
                    'optionC' => 'Menuet des Bois',
                    'optionD' => 'Sérénade de l’Eau',
                    'correctAnswer' => 'C',
                    'category' => 'foret',
                    'order' => 10,
                    'points_value' => 3
                ],
            ],
            'montagne' => [
                [
                    'title' => 'Quel est le leader des Gorons?',
                    'description' => 'Le chef des Gorons',
                    'optionA' => 'Darunia',
                    'optionB' => 'Ganondorf',
                    'optionC' => 'Volvagia',
                    'optionD' => 'Argorok',
                    'correctAnswer' => 'A',
                    'order' => 1,
                    'points_value' => 3
                ],
            ],
                        'bonus' => [
                [
                    'title' => 'Qu\'est-ce que la Triforce?',
                    'description' => 'Un artefact très ancien',
                    'optionA' => 'Trois cristaux',
                    'optionB' => 'Trois triangles sacrés',
                    'optionC' => 'Trois épées',
                    'optionD' => 'Trois clés',
                    'correctAnswer' => 'B',
                    'order' => 1,
                    'points_value' => 5
                ],
            ],
        ];

        foreach ($questionsByCategory as $category => $questions) {

            foreach ($questions as $data) {

                $question = (new Question())
                    ->setTitle($data['title'])
                    ->setDescription($data['description'])
                    ->setOptionA($data['optionA'])
                    ->setOptionB($data['optionB'])
                    ->setOptionC($data['optionC'])
                    ->setOptionD($data['optionD'])
                    ->setCorrectAnswer($data['correctAnswer'])
                    ->setCategory($category)
                    ->setDisplayOrder($data['order'])
                    ->setPointsValue($data['points_value']);

                $manager->persist($question);
            }
        }

        $manager->flush();
    }
}
