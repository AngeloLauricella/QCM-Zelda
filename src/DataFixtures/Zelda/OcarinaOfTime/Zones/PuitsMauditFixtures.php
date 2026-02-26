<?php

namespace App\DataFixtures\Zelda\OcarinaOfTime\Zones;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PuitsMauditFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Créer ou récupérer la zone Le Puits Maudit ---
        $zone = $manager->getRepository(Zone::class)->findOneBy(['name' => 'Le Puits Maudit']);

        if (!$zone) {
            $zone = new Zone();
            $zone->setName('Le Puits Maudit');
            $zone->setDescription('Affrontez les horreurs cachées sous Kakariko dans ce donjon dangereux et mystérieux.');
            $zone->setDisplayOrder(50);
            $zone->setMinPointsToUnlock(900);
            $zone->setIsActive(true);
            // $zone->setIsDangerous(true); // Zone à risque
            $manager->persist($zone);
            $manager->flush(); // ID disponible
        }

        // --- Questions du Puits Maudit ---
        $questions = [
            [
                'title' => 'Quel est le boss caché du Puits ?',
                'description' => 'Affrontez le seigneur des mains déterrées.',
                'optionA' => 'Bongo Bongo',
                'optionB' => 'Dead Hand',
                'optionC' => 'ReDead King',
                'optionD' => 'Dark Link',
                'correctAnswer' => 'B',
                'category' => 'Boss',
                'pointsValue' => 40,
                'penaltyHearts' => 1,
            ],
            [
                'title' => 'Quel objet est indispensable pour naviguer dans le Puits ?',
                'description' => 'Permet de révéler les illusions et les pièges.',
                'optionA' => 'Bouclier Miroir',
                'optionB' => 'Lentille de Vérité',
                'optionC' => 'Marteau des Titans',
                'optionD' => 'Arc',
                'correctAnswer' => 'B',
                'category' => 'Objets',
                'pointsValue' => 35,
                'penaltyHearts' => 1,
            ],
            [
                'title' => 'Quelle créature immobilise Link en l’agrippant ?',
                'description' => 'Attention à sa main géante qui surgit du sol !',
                'optionA' => 'Stalfos',
                'optionB' => 'Main invisible',
                'optionC' => 'ReDead',
                'optionD' => 'Iron Knuckle',
                'correctAnswer' => 'B',
                'category' => 'Ennemis',
                'pointsValue' => 45,
                'penaltyHearts' => 2,
            ],
            [
                'title' => 'Quel piège est courant dans le Puits Maudit ?',
                'description' => 'Les illusions et les chutes sont fréquentes.',
                'optionA' => 'Plateformes invisibles',
                'optionB' => 'Trappes de feu',
                'optionC' => 'Portes scellées par bombes',
                'optionD' => 'Portails dimensionnels',
                'correctAnswer' => 'A',
                'category' => 'Donjon',
                'pointsValue' => 30,
                'penaltyHearts' => 1,
            ],
            [
                'title' => 'Que se passe-t-il si Link accumule trop d’erreurs dans le Puits ?',
                'description' => 'Danger ultime.',
                'optionA' => 'Il perd tous ses points',
                'optionB' => 'Il subit un Game Over',
                'optionC' => 'Il est téléporté au Temple de l’Eau',
                'optionD' => 'Il perd seulement de l’or',
                'correctAnswer' => 'B',
                'category' => 'Risques',
                'pointsValue' => 50,
                'penaltyHearts' => 0,
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
            $question->setPenaltyHearts($qData['penaltyHearts']);
            $question->setPenaltyPoints(0);
            $question->setDisplayOrder($index + 1);
            $question->setIsActive(true);
            $question->setStep(1);
            $manager->persist($question);
        }

        $manager->flush();
    }
}