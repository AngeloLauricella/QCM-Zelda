<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ForetFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Récupérer ou créer la zone "Forêt Perdue" ---
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'La Forêt Perdue']);
        if (!$zone) {
            $zone = new Zone();
            $zone->setName('La Forêt Perdue');
            $zone->setDescription('Plongez dans la Forêt Perdue où vivent les Kokiri. Découvrez les secrets bien gardés de ce monde enchanteur.');
            $zone->setDisplayOrder(1);
            $zone->setMinPointsToUnlock(20);
            $zone->setIsActive(true);
            $manager->persist($zone);
        }

        // --- Questions pour la Forêt Perdue ---
        $questions = [
            [
                'title' => "Qui est l'amie d'enfance de Link dans la forêt ?",
                'description' => "La meilleure amie de Link dans la Forêt Kokiri",
                'optionA' => "Saria",
                'optionB' => "Zelda",
                'optionC' => "Impa",
                'optionD' => "Nabooru",
                'correctAnswer' => "A",
                'category' => "Personnages",
                'pointsValue' => 5,
            ],
            [
                'title' => "Quel est le village des Kokiris ?",
                'description' => "Lieu où Link grandit parmi les Kokiris",
                'optionA' => "Kakariko",
                'optionB' => "Forêt Kokiri",
                'optionC' => "Hyrule",
                'optionD' => "Désert Gerudo",
                'correctAnswer' => "B",
                'category' => "Lieux",
                'pointsValue' => 5,
            ],
            [
                'title' => "Qui guide Link au début de l'aventure ?",
                'description' => "L'esprit protecteur des Kokiris",
                'optionA' => "Navi",
                'optionB' => "Saria",
                'optionC' => "Rauru",
                'optionD' => "Impa",
                'correctAnswer' => "A",
                'category' => "Personnages",
                'pointsValue' => 5,
            ],
            [
                'title' => "Quel objet Link obtient-il pour la première fois dans la forêt ?",
                'description' => "Objet essentiel pour progresser dans le Temple de la Forêt",
                'optionA' => "Arc des Fées",
                'optionB' => "Grappin",
                'optionC' => "Bottes de Plomb",
                'optionD' => "Épée de Kokiri",
                'correctAnswer' => "D",
                'category' => "Objets",
                'pointsValue' => 10,
            ],
            [
                'title' => "Quel est le premier boss que Link doit affronter dans la Forêt ?",
                'description' => "Gardien du Temple de la Forêt",
                'optionA' => "Gohma",
                'optionB' => "Deku Baba",
                'optionC' => "Phantom Ganon",
                'optionD' => "Dark Link",
                'correctAnswer' => "A",
                'category' => "Boss",
                'pointsValue' => 15,
            ],
            [
                'title' => "Quel Sage est réveillé après la purification du Temple de la Forêt ?",
                'description' => "Amie de Link devenue Sage",
                'optionA' => "Ruto",
                'optionB' => "Nabooru",
                'optionC' => "Saria",
                'optionD' => "Impa",
                'correctAnswer' => "C",
                'category' => "Sages",
                'pointsValue' => 10,
            ],
            [
                'title' => "Quel mini-jeu peut-on trouver dans la Forêt Kokiri ?",
                'description' => "Un jeu de cibles lancé par les Kokiris",
                'optionA' => "Cibles à l'arc",
                'optionB' => "Chasse aux insectes",
                'optionC' => "Course de chevaux",
                'optionD' => "Puzzle de pierres",
                'correctAnswer' => "A",
                'category' => "Mini-jeux",
                'pointsValue' => 5,
            ],
            [
                'title' => "Quel arbre ancien habite la Forêt Perdue ?",
                'description' => "Gardien naturel de la forêt",
                'optionA' => "Grand Oak",
                'optionB' => "Arbre Mojo",
                'optionC' => "Géant Sylvain",
                'optionD' => "Deku Père",
                'correctAnswer' => "D",
                'category' => "Boss",
                'pointsValue' => 15,
            ],
            [
                'title' => "Quel objet permet de se déplacer rapidement dans la Forêt ?",
                'description' => "Permet de sauter entre les plateformes",
                'optionA' => "Bottes de Plomb",
                'optionB' => "Grappin",
                'optionC' => "Arc",
                'optionD' => "Flèches explosives",
                'correctAnswer' => "B",
                'category' => "Objets",
                'pointsValue' => 10,
            ],
            [
                'title' => "Quel instrument magique Link découvre dans la forêt ?",
                'description' => "Instrument important pour l'aventure",
                'optionA' => "Flûte Kokiri",
                'optionB' => "Ocarina du Temps",
                'optionC' => "Tambour des Sages",
                'optionD' => "Harpe des Bois",
                'correctAnswer' => "B",
                'category' => "Objets",
                'pointsValue' => 15,
            ],
            [
                'title' => "Qui aide Link à retrouver les objets perdus dans la forêt ?",
                'description' => "Personnage ami de Link",
                'optionA' => "Saria",
                'optionB' => "Navi",
                'optionC' => "Darunia",
                'optionD' => "Impa",
                'correctAnswer' => "A",
                'category' => "Personnages",
                'pointsValue' => 10,
            ],
            [
                'title' => "Quel est le secret pour ouvrir le Temple de la Forêt ?",
                'description' => "Indice pour avancer dans le donjon",
                'optionA' => "Utiliser la Clé Dorée",
                'optionB' => "Jouer la mélodie Saria",
                'optionC' => "Avoir toutes les flèches",
                'optionD' => "Parler au Deku Père",
                'correctAnswer' => "B",
                'category' => "Sages",
                'pointsValue' => 15,
            ],
        ];


        foreach ($questions as $index => $data) {
            $question = new Question();
            $question->setZone($zone);
            $question->setTitle($data['title']);
            $question->setDescription($data['description']);
            $question->setOptionA($data['optionA']);
            $question->setOptionB($data['optionB']);
            $question->setOptionC($data['optionC']);
            $question->setOptionD($data['optionD']);
            $question->setCorrectAnswer($data['correctAnswer']);
            $question->setCategory($data['category']);
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

        $manager->flush();
    }
}
