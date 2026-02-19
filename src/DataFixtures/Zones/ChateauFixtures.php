<?php

namespace App\DataFixtures\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ChateauFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['zones'];
    }

    public function load(ObjectManager $manager): void
    {
        // --- Créer ou récupérer la zone Château d’Hyrule ---
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Château d’Hyrule']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Château d’Hyrule');
            $zone->setDescription('Explorez le majestueux Château d’Hyrule et découvrez les secrets royaux.');
            $zone->setDisplayOrder(2);
            $zone->setMinPointsToUnlock(100);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $manager->flush(); // ID disponible
        }

        // --- Questions du Château d’Hyrule ---
        $questions = [
            [
                'title' => 'Qui règne sur le Château d’Hyrule ?',
                'description' => 'Le souverain ou la souveraine du royaume.',
                'optionA' => 'Zelda',
                'optionB' => 'Ganondorf',
                'optionC' => 'Link',
                'optionD' => 'Impa',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel est le rôle d’Impa dans le château ?',
                'description' => 'Elle protège et conseille la princesse.',
                'optionA' => 'Gardienne',
                'optionB' => 'Servante',
                'optionC' => 'Magicienne',
                'optionD' => 'Chef des gardes',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel objet précieux se trouve dans la salle du trône ?',
                'description' => 'Un artefact que Link doit récupérer ou observer.',
                'optionA' => 'Triforce',
                'optionB' => 'Ocarina du Temps',
                'optionC' => 'Épée de Kokiri',
                'optionD' => 'Bouclier Hylia',
                'correctAnswer' => 'A',
                'category' => 'Objets',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Qui est le conseiller loyal de la princesse ?',
                'description' => 'Celui qui protège le royaume et guide Link.',
                'optionA' => 'Darunia',
                'optionB' => 'Impa',
                'optionC' => 'Saria',
                'optionD' => 'Ruto',
                'correctAnswer' => 'B',
                'category' => 'Personnages',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel est le nom du cheval de Link ?',
                'description' => 'Celui qui l’aide à se déplacer rapidement dans le château et le royaume.',
                'optionA' => 'Epona',
                'optionB' => 'Agahnim',
                'optionC' => 'Nabooru',
                'optionD' => 'Faron',
                'correctAnswer' => 'A',
                'category' => 'Objets',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Combien de sages résident dans le château ?',
                'description' => 'Ils aident à protéger le royaume.',
                'optionA' => '1',
                'optionB' => '2',
                'optionC' => '3',
                'optionD' => '4',
                'correctAnswer' => 'C',
                'category' => 'Personnages',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quelle salle du château abrite les archives secrètes ?',
                'description' => 'Où trouver des indices sur les missions futures ?',
                'optionA' => 'Salle du trône',
                'optionB' => 'Bibliothèque royale',
                'optionC' => 'Dortoirs',
                'optionD' => 'Cuisines',
                'correctAnswer' => 'B',
                'category' => 'Lieux',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel garde est le plus fidèle à la princesse ?',
                'description' => 'Celui qui suit Link dans sa quête.',
                'optionA' => 'Impa',
                'optionB' => 'Darunia',
                'optionC' => 'Ganondorf',
                'optionD' => 'Saria',
                'correctAnswer' => 'A',
                'category' => 'Personnages',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel portail secret permet d’accéder aux jardins royaux ?',
                'description' => 'Un passage caché pour explorer le château.',
                'optionA' => 'Porte de l’Ouest',
                'optionB' => 'Passage sous-terrain',
                'optionC' => 'Tour de l’horloge',
                'optionD' => 'Puits magique',
                'correctAnswer' => 'B',
                'category' => 'Secrets',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel banquet célèbre se déroule dans le château ?',
                'description' => 'Un événement royal connu dans le royaume.',
                'optionA' => 'Fête des fleurs',
                'optionB' => 'Banquet royal',
                'optionC' => 'Cérémonie de l’eau',
                'optionD' => 'Tournoi des archers',
                'correctAnswer' => 'B',
                'category' => 'Événements',
                'pointsValue' => 10,
            ],
            [
                'title' => 'Quel ennemi tente d’infiltrer le château ?',
                'description' => 'Un antagoniste cherchant à semer le chaos.',
                'optionA' => 'Ganondorf',
                'optionB' => 'Moblin',
                'optionC' => 'Keese',
                'optionD' => 'Stalfos',
                'correctAnswer' => 'A',
                'category' => 'Ennemis',
                'pointsValue' => 15,
            ],
            [
                'title' => 'Quel artefact Zelda confie à Link ?',
                'description' => 'Objet clé pour la suite de la quête.',
                'optionA' => 'Ocarina du Temps',
                'optionB' => 'Triforce',
                'optionC' => 'Épée Kokiri',
                'optionD' => 'Bouclier Hylia',
                'correctAnswer' => 'A',
                'category' => 'Objets',
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
            $question->setStep(1); // étape château
            $manager->persist($question);
        }

        $manager->flush();
    }
}
