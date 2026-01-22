<?php

namespace App\Command;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Commande: php bin/console app:create-zelda-questions
 * 
 * Crée les 7 questions narratives du jeu Zelda: Ocarina of Time
 * Chaque question représente une étape majeure de l'histoire
 */
#[AsCommand(
    name: 'app:create-zelda-questions',
    description: 'Crée les questions narratives Zelda pour le jeu',
)]
class CreateZeldaQuestionsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $questions = [
            [
                'step' => 1,
                'title' => 'La Forêt Kokiri - Le Départ',
                'description' => 'Link se réveille dans la forêt paisible. Une mystérieuse prophétie le pousse à quitter son foyer. Qui est cette figure sombre qui hante ses rêves?',
                'optionA' => 'Ganondorf - le Roi des Ténèbres',
                'optionB' => 'Vaati - le Sorcier du Vent',
                'optionC' => 'Demise - le Démon Ancestral',
                'optionD' => 'Majora - la Puissance du Temps',
                'correctAnswer' => 'A',
                'category' => 'introduction',
            ],
            [
                'step' => 2,
                'title' => 'Le Grand Arbre Mojo - La Malédiction',
                'description' => 'En arrivant au Grand Arbre, Link découvre qu\'une ancienne malédiction le tourmente. Quel est ce pouvoir maléfique qui l\'a maudit?',
                'optionA' => 'Une sorcière jalouse',
                'optionB' => 'Ganondorf lui-même',
                'optionC' => 'Un ancien démon',
                'optionD' => 'La tribu des Shadows',
                'correctAnswer' => 'B',
                'category' => 'introduction',
            ],
            [
                'step' => 3,
                'title' => 'Navi - La Voix du Destin',
                'description' => 'Link rencontre Navi, une fée mystérieuse. Elle lui parle d\'une prophétie oubliée. Comment s\'appelle le héros de cette ancienne légende?',
                'optionA' => 'The Hero of Time',
                'optionB' => 'The Child of Light',
                'optionC' => 'The Master of Swords',
                'optionD' => 'The Chosen One',
                'correctAnswer' => 'A',
                'category' => 'introduction',
            ],
            [
                'step' => 4,
                'title' => 'Montagne Goron - Fraternité Inattendue',
                'description' => 'Les Gorons, ces créatures de pierre, sont terrorisés. Quel chef légendaire des Gorons peut aider Link?',
                'optionA' => 'Darunia - le Chef des Gorons',
                'optionB' => 'Volvagia - le Dragon de Feu',
                'optionC' => 'Fire Wizzrobe - le Sorcier',
                'optionD' => 'King Dodongo - le Roi Ancien',
                'correctAnswer' => 'A',
                'category' => 'introduction',
            ],
            [
                'step' => 5,
                'title' => 'Lac Zora - L\'Appel de la Reine',
                'description' => 'Link arrive au Lac Zora et rencontre la reine des Zoras. Elle porte une arme légendaire. Quel est le nom de cette arme magique?',
                'optionA' => 'L\'Épée du Sage',
                'optionB' => 'Le Trident d\'Eau',
                'optionC' => 'La Lame Sainte',
                'optionD' => 'L\'Épée Blanche',
                'correctAnswer' => 'D',
                'category' => 'introduction',
            ],
            [
                'step' => 6,
                'title' => 'Temple du Temps - La Clé Éternelle',
                'description' => 'Link approche du Temple du Temps. Une porte scellée nécessite un objet spécial. Quel est-il?',
                'optionA' => 'La Clé de Temps',
                'optionB' => 'L\'Ocarina du Temps',
                'optionC' => 'Le Cristal Divin',
                'optionD' => 'L\'Épée Maître',
                'correctAnswer' => 'B',
                'category' => 'introduction',
            ],
            [
                'step' => 7,
                'title' => 'Affrontement Final - Le Destin',
                'description' => 'Link se tient face à Ganondorf, le Roi des Ténèbres. Une énergie maléfique émane de lui. Quel pouvoir ancien utilise-t-il pour combattre?',
                'optionA' => 'Le Triforce des Ténèbres',
                'optionB' => 'Le Pouvoir de l\'Épée Maître',
                'optionC' => 'La Magie Ancestrale Sheikah',
                'optionD' => 'La Puissance de la Bête Ganon',
                'correctAnswer' => 'A',
                'category' => 'introduction',
            ],
        ];

        // Supprime les anciennes questions pour ne pas faire de doublons
        $repo = $this->em->getRepository(Question::class);
        $oldQuestions = $repo->findBy(['category' => 'introduction']);
        foreach ($oldQuestions as $oldQuestion) {
            $this->em->remove($oldQuestion);
        }
        $this->em->flush();

        // Crée les nouvelles questions
        foreach ($questions as $qData) {
            $q = new Question();
            $q->setStep($qData['step']);
            $q->setTitle($qData['title']);
            $q->setDescription($qData['description']);
            $q->setOptionA($qData['optionA']);
            $q->setOptionB($qData['optionB']);
            $q->setOptionC($qData['optionC']);
            $q->setOptionD($qData['optionD']);
            $q->setCorrectAnswer($qData['correctAnswer']);
            $q->setCategory($qData['category']);
            $q->setDisplayOrder($qData['step']);
            $q->setPointsValue(3);
            $q->setIsActive(true);

            $this->em->persist($q);
        }

        $this->em->flush();

        $io->success('✅ 7 questions Zelda créées avec succès!');

        return Command::SUCCESS;
    }
}
