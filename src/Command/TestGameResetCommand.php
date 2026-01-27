<?php

namespace App\Command;

use App\Entity\Player;
use App\Entity\User;
use App\Service\GameLogicService;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-game-reset',
    description: 'Teste le flux de redémarrage d\'une partie après Game Over'
)]
class TestGameResetCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private GameLogicService $gameLogic,
        private PlayerService $playerService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('Test du flux de redémarrage de partie');

        // Créer ou récupérer un joueur test
        $io->writeln('1️⃣  Création/récupération d\'un joueur test...');
        $player = $this->createTestPlayer();
        $io->writeln("✓ Joueur: {$player->getName()} (ID: {$player->getId()})");

        // Créer une progression existante
        $io->writeln("\n2️⃣  Création d'une progression initiale...");
        $progress = $this->gameLogic->getOrCreateProgress($player);
        $io->writeln("✓ GameProgress ID: {$progress->getId()}");
        $io->writeln("  - Hearts: {$progress->getHearts()}");
        $io->writeln("  - Points: {$progress->getPoints()}");
        $io->writeln("  - isGameOver: " . ($progress->isGameOver() ? 'true' : 'false'));

        // Simuler une partie jouée
        $io->writeln("\n3️⃣  Simulation d'une partie (Game Over)...");
        $progress->setHearts(0);
        $progress->setPoints(150);
        $progress->setGameOver(true, 'Vous avez perdu tous vos cœurs');
        $this->em->flush();
        $io->writeln("✓ État Game Over défini:");
        $io->writeln("  - Hearts: {$progress->getHearts()}");
        $io->writeln("  - Points: {$progress->getPoints()}");
        $io->writeln("  - isGameOver: " . ($progress->isGameOver() ? 'true' : 'false'));

        // Ajouter une complétion de question
        $io->writeln("\n4️⃣  Ajout d'une complétion de question simulée...");
        // Note: On ne peut pas vraiment ajouter une complétion sans question valide
        // Donc on simule juste le comptage
        $completions = $this->em->getRepository('App\Entity\PlayerEventCompletion')
            ->findBy(['gameProgress' => $progress]);
        $io->writeln("✓ Complétions avant reset: " . count($completions));

        // RESET: Appeler startNewAdventure() comme le contrôleur le ferait
        $io->writeln("\n5️⃣  Redémarrage de la partie (appel startNewAdventure)...");
        $this->gameLogic->startNewAdventure($player);
        $io->writeln("✓ Partie redémarrée et réinitialisée");

        // Vérifier l'état après reset
        $io->writeln("\n6️⃣  Vérification de l'état après reset...");
        $io->writeln("✓ État réinitialisé:");
        $io->writeln("  - Hearts: {$progress->getHearts()}");
        $io->writeln("  - Points: {$progress->getPoints()}");
        $io->writeln("  - isGameOver: " . ($progress->isGameOver() ? 'true' : 'false'));
        $io->writeln("  - currentZoneId: {$progress->getCurrentZoneId()}");

        $completions = $this->em->getRepository('App\Entity\PlayerEventCompletion')
            ->findBy(['gameProgress' => $progress]);
        $io->writeln("  - Complétions après reset: " . count($completions));

        // Vérifications
        $io->writeln("\n7️⃣  Vérifications finales...");
        $checks = [
            ['Hearts = 3', $progress->getHearts() === 3],
            ['Points = 0', $progress->getPoints() === 0],
            ['isGameOver = false', $progress->isGameOver() === false],
            ['currentZoneId > 0', $progress->getCurrentZoneId() > 0],
            ['Complétions supprimées', count($completions) === 0],
        ];

        $allPassed = true;
        foreach ($checks as [$description, $passed]) {
            $status = $passed ? '✅' : '❌';
            $io->writeln("  $status $description");
            if (!$passed) $allPassed = false;
        }

        if ($allPassed) {
            $io->success('✅ Tous les tests sont PASSÉS!');
            return Command::SUCCESS;
        } else {
            $io->error('❌ Certains tests ont ÉCHOUÉ!');
            return Command::FAILURE;
        }
    }

    private function createTestPlayer(): Player
    {
        // Chercher ou créer un utilisateur test
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => 'test@example.com']);

        if (!$user) {
            $user = new User();
            $user->setEmail('test@example.com');
            $user->setUsername('testuser');
            $user->setPassword('hashed_password');
            $user->setRoles(['ROLE_USER']);
            $this->em->persist($user);
            $this->em->flush();
        }

        // Chercher ou créer un joueur
        $player = $this->em->getRepository(Player::class)
            ->findOneBy(['user' => $user]);

        if (!$player) {
            $player = new Player();
            $player->setUser($user);
            $player->setName('Test Player');
            $this->em->persist($player);
            $this->em->flush();
        }

        return $player;
    }
}
