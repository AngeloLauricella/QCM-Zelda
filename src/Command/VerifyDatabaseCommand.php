<?php

namespace App\Command;

use App\Entity\Question;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:verify-database',
    description: 'Vérifie l\'état de la base de données',
)]
class VerifyDatabaseCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Vérification de la Base de Données');

        // Vérifier les questions
        $questionRepo = $this->entityManager->getRepository(Question::class);
        $totalQuestions = count($questionRepo->findAll());
        
        $categories = [
            'introduction' => $questionRepo->countByCategory('introduction'),
            'foret' => $questionRepo->countByCategory('foret'),
            'montagne' => $questionRepo->countByCategory('montagne'),
            'bonus' => $questionRepo->countByCategory('bonus'),
        ];

        $io->section('Questions');
        $io->writeln("Total: <info>$totalQuestions</info> questions");
        foreach ($categories as $cat => $count) {
            $io->writeln("  - <comment>$cat</comment>: <info>$count</info>");
        }

        // Vérifier les joueurs
        $playerRepo = $this->entityManager->getRepository(Player::class);
        $totalPlayers = count($playerRepo->findAll());
        
        $io->section('👥 Joueurs');
        $io->writeln("Total: <info>$totalPlayers</info> joueurs");

        $io->success('Vérification terminée avec succès!');
        return Command::SUCCESS;
    }
}
