<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion des joueurs
 */
class PlayerService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Crée un nouveau joueur
     */
    public function createPlayer(string $name, string $email, ?User $user = null): Player
    {
        $player = new Player();
        $player->setName($name);
        $player->setEmail($email);

        if ($user) {
            $player->setUser($user);
        }

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return $player;
    }

    /**
     * Récupère ou crée un joueur anonyme pour la session
     */
    public function getOrCreateSessionPlayer(string $email): Player
    {
        $playerRepository = $this->entityManager->getRepository(Player::class);
        $player = $playerRepository->findOneBy(['email' => $email]);

        if (!$player) {
            $player = $this->createPlayer('Anonymous_' . uniqid(), $email);
        }

        return $player;
    }

    /**
     * Récupère un joueur par son email
     */
    public function getPlayer(string $email): ?Player
    {
        $playerRepository = $this->entityManager->getRepository(Player::class);
        return $playerRepository->findOneBy(['email' => $email]);
    }

    /**
     * Récupère ou crée un joueur lié à un utilisateur connecté
     */
    public function getOrCreatePlayerForUser(User $user): Player
    {
        $playerRepository = $this->entityManager->getRepository(Player::class);

        $player = $playerRepository->findOneBy(['user' => $user]);

        if (!$player) {
            $player = $this->createPlayer($user->getUsername(), $user->getEmail(), $user);
        }

        return $player;
    }

    /**
     * Supprime un joueur
     */
    public function deletePlayer(Player $player): void
    {
        $this->entityManager->remove($player);
        $this->entityManager->flush();
    }
}
