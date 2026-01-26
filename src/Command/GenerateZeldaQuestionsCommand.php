<?php

namespace App\Command;

use App\Entity\Zone;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-zelda-questions',
    description: 'Génère les questions du quiz Zelda Ocarina of Time'
)]
class GenerateZeldaQuestionsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Nettoyer les données existantes
        $io->section('Suppression des données existantes...');
        $this->cleanExistingData();
        $io->writeln('✓ Données existantes supprimées');

        // Générer les zones et questions
        $io->section('Génération des questions Zelda...');
        $this->generateZeldaContent();
        $io->writeln('✓ Zones et questions générées');

        $io->success('Questions Zelda générées avec succès!');
        return Command::SUCCESS;
    }

    private function cleanExistingData(): void
    {
        // Supprimer toutes les questions
        $this->em->createQuery('DELETE FROM App\Entity\Question')->execute();
        // Supprimer toutes les zones
        $this->em->createQuery('DELETE FROM App\Entity\Zone')->execute();
        $this->em->flush();
    }

    private function generateZeldaContent(): void
    {
        $zones = $this->createZones();
        $this->createQuestions($zones);
    }

    private function createZones(): array
    {
        $zonesData = [
            ['name' => 'Forêt Kokiri', 'description' => 'Le début de l\'aventure dans la forêt de Kokiri', 'order' => 1],
            ['name' => 'Temple Deku', 'description' => 'Le premier temple du Roi Dodongo', 'order' => 2],
            ['name' => 'Château d\'Hyrule', 'description' => 'L\'audience avec la princesse Zelda', 'order' => 3],
            ['name' => 'Ranch Lon Lon', 'description' => 'Chez Talon et les chevaux', 'order' => 4],
            ['name' => 'Village Cocorico', 'description' => 'Un village rempli de poules', 'order' => 5],
            ['name' => 'Grotte Dodongo', 'description' => 'La grotte aux Dodongos', 'order' => 6],
            ['name' => 'Ventre de Jabu-Jabu', 'description' => 'À l\'intérieur du grand poisson', 'order' => 7],
            ['name' => 'Temple du Temps', 'description' => 'Le temple sacré du temps', 'order' => 8],
            ['name' => 'Temple de la Forêt', 'description' => 'Le premier temple adulte', 'order' => 9],
            ['name' => 'Temple du Feu', 'description' => 'Un temple ardent de lave', 'order' => 10],
            ['name' => 'Lac Hylia', 'description' => 'Le grand lac d\'Hyrule', 'order' => 11],
            ['name' => 'Temple de l\'Eau', 'description' => 'Un temple submergé sous l\'eau', 'order' => 12],
            ['name' => 'Puits', 'description' => 'Un puits souterrain rempli d\'énigmes', 'order' => 13],
            ['name' => 'Village Fantôme', 'description' => 'Un village peuplé de créatures spectrales', 'order' => 14],
            ['name' => 'Temple de l\'Ombre', 'description' => 'Un temple sombre et menaçant', 'order' => 15],
            ['name' => 'Vallée Gerudo', 'description' => 'La forteresse des Gerudos', 'order' => 16],
            ['name' => 'Temple de l\'Esprit', 'description' => 'Le dernier temple avant le final', 'order' => 17],
            ['name' => 'Château de Ganon', 'description' => 'Le repaire du roi du mal', 'order' => 18],
        ];

        $zones = [];
        foreach ($zonesData as $data) {
            $zone = new Zone();
            $zone->setName($data['name']);
            $zone->setDescription($data['description']);
            $zone->setDisplayOrder($data['order']);
            $zone->setMinPointsToUnlock($data['order'] * 50);
            $zone->setIsActive(true);
            $this->em->persist($zone);
            $zones[$data['name']] = $zone;
        }
        $this->em->flush();
        return $zones;
    }

    private function createQuestions(array $zones): void
    {
        $questionsData = $this->getQuestionsData();

        foreach ($questionsData as $zoneName => $questions) {
            $zone = $zones[$zoneName] ?? null;
            if (!$zone) continue;

            foreach ($questions as $index => $qData) {
                $question = new Question();
                $question->setZone($zone);
                $question->setTitle($qData['title']);
                $question->setDescription($qData['description']);
                $question->setOptionA($qData['optionA']);
                $question->setOptionB($qData['optionB']);
                $question->setOptionC($qData['optionC']);
                $question->setOptionD($qData['optionD']);
                $question->setCorrectAnswer($qData['correct']);
                $question->setCategory($qData['category']);
                $question->setDisplayOrder($index + 1);
                $question->setRewardHearts($qData['rewardHearts']);
                $question->setRewardPoints($qData['rewardPoints']);
                $question->setPenaltyHearts($qData['penaltyHearts']);
                $question->setPenaltyPoints($qData['penaltyPoints']);
                $question->setIsActive(true);
                $question->setIsOneTimeOnly(false);
                $question->setStep(1);

                $this->em->persist($question);
            }
        }

        $this->em->flush();
    }

    private function getQuestionsData(): array
    {
        return [
            'Forêt Kokiri' => [
                [
                    'title' => 'Qui est ta meilleure amie à Kokiri?',
                    'description' => 'Une question sur tes compagnons de la forêt Kokiri',
                    'optionA' => 'Mido',
                    'optionB' => 'Saria',
                    'optionC' => 'Navi',
                    'optionD' => 'The Great Deku Tree',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel est le nom du guide de Link?',
                    'description' => 'La petite fée qui t\'accompagne',
                    'optionA' => 'Nabooru',
                    'optionB' => 'Navi',
                    'optionC' => 'Navelle',
                    'optionD' => 'Nevé',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Qu\'obtiens-tu en vainquant le Grand Arbre Deku?',
                    'description' => 'Le premier temple principal',
                    'optionA' => 'L\'épée Kokiri',
                    'optionB' => 'L\'Émeraude de la Forêt',
                    'optionC' => 'Le carquois de flèches',
                    'optionD' => 'La bombe',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quelle est la faiblesse de Gohma?',
                    'description' => 'Phantom Ganon utilise quelle technique pour t\'attaquer dans le Temple de la Forêt?',
                    'optionA' => 'Elle ne peut pas être blessée',
                    'optionB' => 'Elle est vulnérable au feu',
                    'optionC' => 'Elle ne peut être blessée que par son propre œil',
                    'optionD' => 'Elle est sensible à la glace',
                    'correct' => 'C',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 100,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Temple Deku' => [
                [
                    'title' => 'Quel type de créature est le Roi Dodongo?',
                    'description' => 'Le boss du Temple Deku',
                    'optionA' => 'Un dragon',
                    'optionB' => 'Un énorme lézard',
                    'optionC' => 'Un escargot géant',
                    'optionD' => 'Un taureau',
                    'correct' => 'B',
                    'category' => 'creatures',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel objet utiles-tu pour résoudre les énigmes du Temple Deku?',
                    'description' => 'Un instrument important du temple',
                    'optionA' => 'L\'Arc',
                    'optionB' => 'Les Bombes',
                    'optionC' => 'Le Grappin',
                    'optionD' => 'La Sarbacane',
                    'correct' => 'B',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Qu\'obtiens-tu après avoir vaincu le Roi Dodongo?',
                    'description' => 'Le prix principal du Temple Deku',
                    'optionA' => 'L\'Émeraude du Feu',
                    'optionB' => 'L\'Émeraude Bleue',
                    'optionC' => 'L\'Émeraude Verte',
                    'optionD' => 'L\'Émeraude de la Forêt',
                    'correct' => 'C',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Comment vaincs-tu le Roi Dodongo?',
                    'description' => 'La stratégie pour vaincre le boss',
                    'optionA' => 'En tirant des flèches',
                    'optionB' => 'En lui jetant ses propres bombes',
                    'optionC' => 'En le frappant avec l\'épée en Z-target',
                    'optionD' => 'Avec la sarbacane',
                    'correct' => 'B',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 100,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Château d\'Hyrule' => [
                [
                    'title' => 'Quel est le nom complet de la Princesse Zelda?',
                    'description' => 'La dirigeante du Royaume d\'Hyrule',
                    'optionA' => 'Zelda Farore',
                    'optionB' => 'Zelda Nayru',
                    'optionC' => 'Zelda Hylia',
                    'optionD' => 'Zelda Din',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Pourquoi Zelda te fait-elle rencontrer?',
                    'description' => 'La raison de votre première entrevue',
                    'optionA' => 'Pour te recruter contre Ganondorf',
                    'optionB' => 'Pour te donner un cadeau',
                    'optionC' => 'Pour te confier sa flûte',
                    'optionD' => 'Pour te tester',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le nom du menace du Château d\'Hyrule?',
                    'description' => 'L\'ennemi principal du château',
                    'optionA' => 'Ganondorf',
                    'optionB' => 'Ganon',
                    'optionC' => 'Vaati',
                    'optionD' => 'Demise',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 80,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Ranch Lon Lon' => [
                [
                    'title' => 'Comment s\'appelle le propriétaire du ranch?',
                    'description' => 'L\'éleveur de chevaux',
                    'optionA' => 'Talon',
                    'optionB' => 'Talo',
                    'optionC' => 'Tal',
                    'optionD' => 'Talus',
                    'correct' => 'A',
                    'category' => 'characters',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel animal mythique peux-tu monter au ranch?',
                    'description' => 'Le compagnon principal du ranch',
                    'optionA' => 'Un Loftwing',
                    'optionB' => 'Un cheval',
                    'optionC' => 'Un Dragon',
                    'optionD' => 'Un Stalhorse',
                    'correct' => 'B',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le nom du cheval particulier?',
                    'description' => 'Le cheval spécial du ranch',
                    'optionA' => 'Éponine',
                    'optionB' => 'Tempête',
                    'optionC' => 'Impa\'s Steed',
                    'optionD' => 'Epona',
                    'correct' => 'D',
                    'category' => 'characters',
                    'rewardHearts' => 2,
                    'rewardPoints' => 90,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Village Cocorico' => [
                [
                    'title' => 'Pourquoi les poules du Village Cocorico sont-elles importantes?',
                    'description' => 'Le rôle des poules dans le village',
                    'optionA' => 'Elles donnent des œufs magiques',
                    'optionB' => 'Elles sont décoratives',
                    'optionC' => 'Elles attaquent les intrus',
                    'optionD' => 'Elles gardent le village',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel instrument peux-tu obtenir au Village Cocorico?',
                    'description' => 'Un objet important pour progresser',
                    'optionA' => 'La Flûte Ocarina',
                    'optionB' => 'La Sarbacane',
                    'optionC' => 'La Lyre',
                    'optionD' => 'La Trompette',
                    'correct' => 'A',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss du Village Cocorico?',
                    'description' => 'L\'ennemi à vaincre ici',
                    'optionA' => 'Une Reine des Poules géante',
                    'optionB' => 'Le Maire',
                    'optionC' => 'Une sorcière',
                    'optionD' => 'Un Stalfos',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 85,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Grotte Dodongo' => [
                [
                    'title' => 'Quel type de créatures habitent la Grotte Dodongo?',
                    'description' => 'Les habitants de la grotte',
                    'optionA' => 'Des Tektites',
                    'optionB' => 'Des Dodongos',
                    'optionC' => 'Des Stalchildren',
                    'optionD' => 'Des Moblins',
                    'correct' => 'B',
                    'category' => 'creatures',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'À quoi servent les cristaux rouge et bleu dans la grotte?',
                    'description' => 'Les éléments spéciaux de la grotte',
                    'optionA' => 'À décorer la grotte',
                    'optionB' => 'À résoudre des énigmes',
                    'optionC' => 'À donner des pouvoirs',
                    'optionD' => 'À attirer les trésors',
                    'correct' => 'B',
                    'category' => 'puzzles',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel boss dois-tu affronter ici?',
                    'description' => 'Le boss principal de la grotte',
                    'optionA' => 'Le Roi des Dodongos Rouges',
                    'optionB' => 'Un Grosse Araignée',
                    'optionC' => 'Tektites Géants',
                    'optionD' => 'Un Golem de Pierre',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 95,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Ventre de Jabu-Jabu' => [
                [
                    'title' => 'Qui est Jabu-Jabu?',
                    'description' => 'Le créature dans laquelle tu entres',
                    'optionA' => 'Une baleine',
                    'optionB' => 'Un énorme poisson',
                    'optionC' => 'Une tortue',
                    'optionD' => 'Un serpent marin',
                    'correct' => 'B',
                    'category' => 'creatures',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Que dois-tu faire pour entrer dans le Ventre de Jabu-Jabu?',
                    'description' => 'L\'accès au temple',
                    'optionA' => 'Parler au poisson',
                    'optionB' => 'Jouer une mélodie',
                    'optionC' => 'Résoudre une énigme',
                    'optionD' => 'Attendre à côté',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Comment vaincs-tu le boss du Ventre de Jabu-Jabu?',
                    'description' => 'La stratégie pour le boss',
                    'optionA' => 'En le frappant directement',
                    'optionB' => 'En le lançant des électrodes électrique',
                    'optionC' => 'En le laissant t\'avaler puis en frappant l\'intérieur',
                    'optionD' => 'Avec le grappin',
                    'correct' => 'C',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 100,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Temple du Temps' => [
                [
                    'title' => 'Que fais-tu au Temple du Temps?',
                    'description' => 'L\'événement clé du temple',
                    'optionA' => 'Tu combats le Roi du Temps',
                    'optionB' => 'Tu voyages 7 ans dans le futur',
                    'optionC' => 'Tu récupères l\'Ocarina du Temps',
                    'optionD' => 'Tu défies Ganondorf',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 30,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Qu\'est-ce que le Maître de l\'Épée?',
                    'description' => 'L\'épée légendaire du temple',
                    'optionA' => 'Une épée dorée',
                    'optionB' => 'L\'épée de temps',
                    'optionC' => 'L\'épée de Toujours',
                    'optionD' => 'L\'épée des Sages',
                    'correct' => 'C',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel boss affrontes-tu après ce voyage temporel?',
                    'description' => 'L\'ennemi qui t\'attend',
                    'optionA' => 'Phantom Ganon',
                    'optionB' => 'L\'Ombre de Ganondorf',
                    'optionC' => 'Un Golem Noir',
                    'optionD' => 'Le Roi du Temps',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 110,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Temple de la Forêt' => [
                [
                    'title' => 'Quel élément domine le Temple de la Forêt?',
                    'description' => 'L\'élément principal du temple',
                    'optionA' => 'L\'eau',
                    'optionB' => 'La forêt et les plantes',
                    'optionC' => 'Le feu',
                    'optionD' => 'L\'air',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Qu\'est-ce que tu obtiens en complétant le Temple de la Forêt?',
                    'description' => 'La récompense du temple',
                    'optionA' => 'L\'Émeraude Verte',
                    'optionB' => 'L\'Émeraude Bleue',
                    'optionC' => 'L\'Émeraude de la Forêt',
                    'optionD' => 'L\'Émeraude du Temps',
                    'correct' => 'C',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 30,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Comment vaincs-tu Phantom Ganon?',
                    'description' => 'La stratégie contre le boss',
                    'optionA' => 'Il lance des boules d\'énergie depuis les tableaux',
                    'optionB' => 'Il invoque des Stalfos',
                    'optionC' => 'Il se téléporte et frappe avec son épée',
                    'optionD' => 'Il crée des clones de lui-même',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 150,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Temple du Feu' => [
                [
                    'title' => 'Quel est l\'élément principal du Temple du Feu?',
                    'description' => 'L\'essence du temple',
                    'optionA' => 'La glace',
                    'optionB' => 'Le magma et le feu',
                    'optionC' => 'L\'électricité',
                    'optionD' => 'Les ténèbres',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Qu\'est-ce que la Tunic Rouges?',
                    'description' => 'L\'équipement spécial du temple',
                    'optionA' => 'Une armor dorée',
                    'optionB' => 'Une armor verte',
                    'optionC' => 'Une armor rouges qui protège du feu',
                    'optionD' => 'Une armor bleue',
                    'correct' => 'C',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss du Temple du Feu?',
                    'description' => 'L\'ennemi principal du temple',
                    'optionA' => 'Volvagia',
                    'optionB' => 'Armos',
                    'optionC' => 'Lava Bubble',
                    'optionD' => 'Dodongo de Feu',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 150,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Lac Hylia' => [
                [
                    'title' => 'Que trouves-tu au Lac Hylia?',
                    'description' => 'Les éléments clés du lac',
                    'optionA' => 'Un autre château',
                    'optionB' => 'Une île avec des secrets',
                    'optionC' => 'Un marché secret',
                    'optionD' => 'Une grotte souterraine',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel instrument utiles-tu pour traverser le lac?',
                    'description' => 'L\'objet pour explorer le lac',
                    'optionA' => 'Une bombe',
                    'optionB' => 'Un grappin',
                    'optionC' => 'Un bateau',
                    'optionD' => 'Une nage magique',
                    'correct' => 'B',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel monstre garde Lac Hylia?',
                    'description' => 'L\'ennemi du lac',
                    'optionA' => 'Une grande Araignée',
                    'optionB' => 'Un Tektite géant',
                    'optionC' => 'Un Lézalfo géant',
                    'optionD' => 'Un Triforce Gardien',
                    'correct' => 'C',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 130,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Temple de l\'Eau' => [
                [
                    'title' => 'Quel est l\'élément principal du Temple de l\'Eau?',
                    'description' => 'L\'essence du temple',
                    'optionA' => 'La glace',
                    'optionB' => 'L\'eau et les niveaux',
                    'optionC' => 'Le sable',
                    'optionD' => 'L\'air',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Qu\'est-ce que la Tunic Bleue?',
                    'description' => 'L\'équipement pour respirer',
                    'optionA' => 'Une armor qui rend invisible',
                    'optionB' => 'Une armor qui rend magnétique',
                    'optionC' => 'Une armor bleue qui permet de respirer sous l\'eau',
                    'optionD' => 'Une armor qui augmente la force',
                    'correct' => 'C',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss du Temple de l\'Eau?',
                    'description' => 'L\'ennemi principal du temple',
                    'optionA' => 'Gyorg',
                    'optionB' => 'Lemme',
                    'optionC' => 'Gohma Aquatique',
                    'optionD' => 'Ruto Contrôlée',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 150,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Puits' => [
                [
                    'title' => 'À quoi sert le Puits dans le jeu?',
                    'description' => 'L\'objectif du puits',
                    'optionA' => 'À récupérer de l\'argent',
                    'optionB' => 'À trouver des secrets',
                    'optionC' => 'À combattre des boss optionnels',
                    'optionD' => 'À apprendre des mélodies',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel objet trouves-tu au fond du Puits?',
                    'description' => 'Le trésor principal',
                    'optionA' => 'L\'épée de cristal',
                    'optionB' => 'L\'épée Biggoron',
                    'optionC' => 'L\'épée Dorée',
                    'optionD' => 'L\'épée d\'Argent',
                    'correct' => 'B',
                    'category' => 'items',
                    'rewardHearts' => 1,
                    'rewardPoints' => 30,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss du Puits?',
                    'description' => 'L\'ennemi principal',
                    'optionA' => 'Les Stalfos',
                    'optionB' => 'Twinrova',
                    'optionC' => 'Shadow Link',
                    'optionD' => 'Dead Hand',
                    'correct' => 'C',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 140,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Village Fantôme' => [
                [
                    'title' => 'Pourquoi le Village Fantôme est-il maudit?',
                    'description' => 'L\'histoire du village',
                    'optionA' => 'À cause d\'une malédiction ancienne',
                    'optionB' => 'À cause de Ganondorf',
                    'optionC' => 'À cause d\'une fée morte',
                    'optionD' => 'À cause d\'un sorcier',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Qu\'est-ce qui peut briser la malédiction du Village Fantôme?',
                    'description' => 'La solution à la malédiction',
                    'optionA' => 'L\'Ocarina du Temps',
                    'optionB' => 'L\'épée Biggoron',
                    'optionC' => 'Une chason spéciale',
                    'optionD' => 'Le Triforce',
                    'correct' => 'C',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss du Village Fantôme?',
                    'description' => 'L\'ennemi du village',
                    'optionA' => 'Garo Master',
                    'optionB' => 'Phantom Beast',
                    'optionC' => 'Sorceress',
                    'optionD' => 'Bongo Bongo',
                    'correct' => 'D',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 140,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Temple de l\'Ombre' => [
                [
                    'title' => 'Quel est l\'élément du Temple de l\'Ombre?',
                    'description' => 'L\'essence du temple',
                    'optionA' => 'Les ténèbres',
                    'optionB' => 'Le feu',
                    'optionC' => 'La glace',
                    'optionD' => 'L\'électricité',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel sage est enfermé dans le Temple de l\'Ombre?',
                    'description' => 'Le sage du temple',
                    'optionA' => 'Nabooru',
                    'optionB' => 'Impa',
                    'optionC' => 'Sheik',
                    'optionD' => 'Zelda',
                    'correct' => 'B',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss du Temple de l\'Ombre?',
                    'description' => 'L\'ennemi principal',
                    'optionA' => 'Phantom Beast',
                    'optionB' => 'Twinrova',
                    'optionC' => 'Dark Link',
                    'optionD' => 'Mummified Ganon',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 160,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Vallée Gerudo' => [
                [
                    'title' => 'Qui sont les Gerudos?',
                    'description' => 'Les habitants de la vallée',
                    'optionA' => 'Des chevaliers',
                    'optionB' => 'Des guerrières',
                    'optionC' => 'Des magiciennes',
                    'optionD' => 'Des monstres',
                    'correct' => 'B',
                    'category' => 'creatures',
                    'rewardHearts' => 1,
                    'rewardPoints' => 15,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel est le nom de la Reine des Gerudos?',
                    'description' => 'La dirigeante de la vallée',
                    'optionA' => 'Nabooru',
                    'optionB' => 'Ganondorf',
                    'optionC' => 'Koume',
                    'optionD' => 'Kotake',
                    'correct' => 'A',
                    'category' => 'characters',
                    'rewardHearts' => 1,
                    'rewardPoints' => 20,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss de la Vallée Gerudo?',
                    'description' => 'L\'ennemi de la vallée',
                    'optionA' => 'Twinrova',
                    'optionB' => 'Gerudo Guard',
                    'optionC' => 'Nabooru Contrôlée',
                    'optionD' => 'Shadow Ganondorf',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 150,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Temple de l\'Esprit' => [
                [
                    'title' => 'Quel élément maîtrise le Temple de l\'Esprit?',
                    'description' => 'L\'essence du temple',
                    'optionA' => 'L\'Esprit',
                    'optionB' => 'La Lumière',
                    'optionC' => 'Les Ténèbres',
                    'optionD' => 'Le Vide',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 25,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Quel sage est emprisonné au Temple de l\'Esprit?',
                    'description' => 'Le sage du temple',
                    'optionA' => 'Nabooru',
                    'optionB' => 'Impa',
                    'optionC' => 'Saria',
                    'optionD' => 'Darunia',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 30,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS - Quel est le boss du Temple de l\'Esprit?',
                    'description' => 'L\'ennemi principal',
                    'optionA' => 'Koume et Kotake',
                    'optionB' => 'Phantom Ganon',
                    'optionC' => 'Gleeok',
                    'optionD' => 'Poe Collector',
                    'correct' => 'A',
                    'category' => 'boss',
                    'rewardHearts' => 2,
                    'rewardPoints' => 170,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
            'Château de Ganon' => [
                [
                    'title' => 'Quel est le véritable nom du roi malfaisant?',
                    'description' => 'L\'ennemi ultime',
                    'optionA' => 'Ganondorf',
                    'optionB' => 'Ganon',
                    'optionC' => 'Gibdo',
                    'optionD' => 'Gleeok',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 30,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'Pourquoi Ganondorf cherche-t-il à conquérir Hyrule?',
                    'description' => 'La motivation du mal',
                    'optionA' => 'Pour le Triforce',
                    'optionB' => 'Pour la vengeance',
                    'optionC' => 'Pour la destruction',
                    'optionD' => 'Pour le pouvoir éternel',
                    'correct' => 'A',
                    'category' => 'story',
                    'rewardHearts' => 1,
                    'rewardPoints' => 35,
                    'penaltyHearts' => 2,
                    'penaltyPoints' => 0
                ],
                [
                    'title' => 'BOSS FINAL - Quelle est la forme ultime de Ganon?',
                    'description' => 'Le vrai visage du mal',
                    'optionA' => 'Un sorcier noir',
                    'optionB' => 'Une bête léonine (Beast Ganon)',
                    'optionC' => 'Un dragon éternel',
                    'optionD' => 'Un esprit immortel',
                    'correct' => 'B',
                    'category' => 'boss',
                    'rewardHearts' => 3,
                    'rewardPoints' => 200,
                    'penaltyHearts' => 5,
                    'penaltyPoints' => 0
                ],
            ],
        ];
    }
}
