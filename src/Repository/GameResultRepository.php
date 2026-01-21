<?php

namespace App\Repository;

use App\Entity\GameResult;
use App\Entity\Player;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameResult>
 */
class GameResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameResult::class);
    }

    /**
     * Récupère les résultats d'un joueur
     */
    public function findByPlayer(Player $player): array
    {
        return $this->createQueryBuilder('gr')
            ->andWhere('gr.player = :player')
            ->setParameter('player', $player)
            ->orderBy('gr.answeredAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère le résultat d'un joueur pour une question
     */
    public function findByPlayerAndQuestion(Player $player, Question $question): ?GameResult
    {
        return $this->findOneBy([
            'player' => $player,
            'question' => $question,
        ]);
    }

    /**
     * Compte les bonnes réponses d'un joueur
     */
    public function countCorrectAnswers(Player $player): int
    {
        return (int)$this->createQueryBuilder('gr')
            ->select('COUNT(gr.id)')
            ->andWhere('gr.player = :player')
            ->andWhere('gr.isCorrect = true')
            ->setParameter('player', $player)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte les mauvaises réponses d'un joueur
     */
    public function countWrongAnswers(Player $player): int
    {
        return (int)$this->createQueryBuilder('gr')
            ->select('COUNT(gr.id)')
            ->andWhere('gr.player = :player')
            ->andWhere('gr.isCorrect = false')
            ->setParameter('player', $player)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère les résultats par catégorie pour un joueur
     */
    public function findByCategoryAndPlayer(Player $player, string $category): array
    {
        return $this->createQueryBuilder('gr')
            ->andWhere('gr.player = :player')
            ->andWhere('gr.question->category = :category')
            ->setParameter('player', $player)
            ->setParameter('category', $category)
            ->orderBy('gr.answeredAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
