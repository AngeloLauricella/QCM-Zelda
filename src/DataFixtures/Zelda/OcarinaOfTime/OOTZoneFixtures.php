<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime;

use App\Entity\Question;
use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OOTZoneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Récupérer les zones existantes ---
        /** @var Zone|null $foret */
        $foret = $manager->getRepository(Zone::class)->find(19);
        /** @var Zone|null $chateau */
        $chateau = $manager->getRepository(Zone::class)->find(20);
        /** @var Zone|null $desert */
        $desert = $manager->getRepository(Zone::class)->find(21);
        /** @var Zone|null $glace */
        $glace = $manager->getRepository(Zone::class)->find(22);
        /** @var Zone|null $eau */
        $eau = $manager->getRepository(Zone::class)->find(23);
        /** @var Zone|null $montagne */
        $montagne = $manager->getRepository(Zone::class)->find(24);

        // --- Questions par zone ---
        $questionsByZone = [
            $foret ? $foret->getId() : 0 => [
                'zone' => $foret,
                'questions' => [
                    [
                        'title' => "Qui est l'amie d'enfance de Link dans la forêt?",
                        'description' => "La meilleure amie de Link dans la Forêt Kokiri",
                        'optionA' => "Saria",
                        'optionB' => "Zelda",
                        'optionC' => "Impa",
                        'optionD' => "Nabooru",
                        'correctAnswer' => "A",
                        'pointsValue' => 5,
                    ],
                    [
                        'title' => "Quel village est caché dans la Forêt Kokiri?",
                        'description' => "Le lieu où tous les enfants Kokiri vivent",
                        'optionA' => "Village Cocorico",
                        'optionB' => "Kokiri Forest",
                        'optionC' => "Ordon Village",
                        'optionD' => "Hyrule Castle Town",
                        'correctAnswer' => "B",
                        'pointsValue' => 5,
                    ],
                    [
                        'title' => "Quel objet Link doit obtenir avant d’entrer dans l’Arbre Mojo?",
                        'description' => "Équipement essentiel pour se défendre",
                        'optionA' => "Épée Kokiri",
                        'optionB' => "Bouclier Hylien",
                        'optionC' => "Arc",
                        'optionD' => "Bâton Mojo",
                        'correctAnswer' => "A",
                        'pointsValue' => 5,
                    ],
                    [
                        'title' => "Quel ennemi attaque Link dans les Bois Perdus?",
                        'description' => "Un ennemi fréquent dans la forêt",
                        'optionA' => "Moblin",
                        'optionB' => "Stalfos",
                        'optionC' => "Lizalfos",
                        'optionD' => "Bokoblin",
                        'correctAnswer' => "A",
                        'pointsValue' => 5,
                    ],
                    [
                        'title' => "Quel lieu secret se trouve au fond des Bois Perdus?",
                        'description' => "Une clairière protégée par des ennemis",
                        'optionA' => "Bosquet Sacré",
                        'optionB' => "Temple du Temps",
                        'optionC' => "Mont du Péril",
                        'optionD' => "Lac Hylia",
                        'correctAnswer' => "A",
                        'pointsValue' => 5,
                    ],
                    [
                        'title' => "Quel personnage apprend à Link le Chant de Saria?",
                        'description' => "Une amie d’enfance vivant dans la forêt",
                        'optionA' => "Zelda",
                        'optionB' => "Saria",
                        'optionC' => "Impa",
                        'optionD' => "Malon",
                        'correctAnswer' => "B",
                        'pointsValue' => 5,
                    ],
                    [
                        'title' => "Quel temple se situe dans le Bosquet Sacré?",
                        'description' => "Un ancien sanctuaire envahi par des créatures",
                        'optionA' => "Temple du Feu",
                        'optionB' => "Temple de l’Esprit",
                        'optionC' => "Temple de la Forêt",
                        'optionD' => "Temple de l’Ombre",
                        'correctAnswer' => "C",
                        'pointsValue' => 10,
                    ],
                    [
                        'title' => "Quel objet Link obtient dans le Temple de la Forêt?",
                        'description' => "Un outil permettant de s’accrocher à des cibles lointaines",
                        'optionA' => "Arc des Fées",
                        'optionB' => "Grappin",
                        'optionC' => "Masse des Titans",
                        'optionD' => "Bottes de plomb",
                        'correctAnswer' => "B",
                        'pointsValue' => 10,
                    ],
                    [
                        'title' => "Quel boss Link affronte à la fin du Temple de la Forêt?",
                        'description' => "Une incarnation fantomatique du roi du désert",
                        'optionA' => "Phantom Ganon",
                        'optionB' => "Volvagia",
                        'optionC' => "Bongo Bongo",
                        'optionD' => "Morpha",
                        'correctAnswer' => "A",
                        'pointsValue' => 15,
                    ],
                    [
                        'title' => "Quel Sage est réveillé après la purification du Temple de la Forêt?",
                        'description' => "Une amie de Link devenue Sage",
                        'optionA' => "Ruto",
                        'optionB' => "Nabooru",
                        'optionC' => "Saria",
                        'optionD' => "Impa",
                        'correctAnswer' => "C",
                        'pointsValue' => 15,
                    ],
                ]
            ],
            $chateau ? $chateau->getId() : 0 => [
                'zone' => $chateau,
                'questions' => [
                    [
                        'title' => "Qui règne sur le Château d'Hyrule?",
                        'description' => "La princesse du Château d'Hyrule",
                        'optionA' => "Zelda",
                        'optionB' => "Impa",
                        'optionC' => "Malon",
                        'optionD' => "Ruto",
                        'correctAnswer' => "A",
                        'pointsValue' => 5,
                    ],
                    [
                        'title' => "Quel est le nom complet de Link?",
                        'description' => "Connaissez-vous le vrai nom du héros?",
                        'optionA' => "Link Hylien",
                        'optionB' => "Link de Kokiri",
                        'optionC' => "Link du Destin",
                        'optionD' => "Link Temporel",
                        'correctAnswer' => "A",
                        'pointsValue' => 10,
                    ],
                ]
            ],
            // Ajouter les autres zones ici de la même façon...
        ];

        // --- Création des questions ---
        foreach ($questionsByZone as $zoneData) {
            /** @var Zone|null $zoneObject */
            $zoneObject = $zoneData['zone'];

            if (!$zoneObject) {
                continue; // ignore si la zone n'existe pas
            }

            foreach ($zoneData['questions'] as $index => $data) {
                $question = new Question();
                $question->setZone($zoneObject);
                $question->setTitle($data['title']);
                $question->setDescription($data['description']);
                $question->setOptionA($data['optionA']);
                $question->setOptionB($data['optionB']);
                $question->setOptionC($data['optionC']);
                $question->setOptionD($data['optionD']);
                $question->setCorrectAnswer($data['correctAnswer']);
                $question->setCategory($zoneObject->getName());
                $question->setPointsValue($data['pointsValue']);
                $question->setRewardHearts(0);
                $question->setRewardPoints($data['pointsValue']);
                $question->setPenaltyHearts(0);
                $question->setPenaltyPoints(-($data['pointsValue'] / 2));
                $question->setDisplayOrder($index + 1);
                $question->setStep(1);
                $question->setIsActive(true);

                $manager->persist($question);
            }
        }

        $manager->flush();
    }
}
