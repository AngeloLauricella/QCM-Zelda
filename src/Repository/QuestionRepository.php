<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * Récupère une question par ID
     */
    public function findOneById(int $id): ?Question
    {
        return $this->find($id);
    }

    /**
     * Récupère toutes les questions d'une catégorie
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.category = :category')
            ->setParameter('category', $category)
            ->orderBy('q.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère une question aléatoire d'une catégorie
     */
    public function findRandomByCategory(string $category): ?Question
    {
        $questions = $this->findByCategory($category);
        return !empty($questions) ? $questions[array_rand($questions)] : null;
    }

    /**
     * Compte le nombre de questions par catégorie
     */
    public function countByCategory(string $category): int
    {
        return (int) $this->createQueryBuilder('q')
            ->select('COUNT(q.id)')
            ->andWhere('q.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère toutes les catégories disponibles
     */
    public function findAllCategories(): array
    {
        $results = $this->createQueryBuilder('q')
            ->select('DISTINCT q.category')
            ->orderBy('q.category', 'ASC')
            ->getQuery()
            ->getResult();

        return array_column($results, 'category');
    }

    /**
     * Récupère la première question active
     */
    public function findFirstActiveQuestion(): ?Question
    {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.zone', 'z')
            ->andWhere('q.isActive = :active')
            ->andWhere('z.isActive = :zoneActive')
            ->setParameter('active', true)
            ->setParameter('zoneActive', true)
            ->orderBy('z.displayOrder', 'ASC')
            ->addOrderBy('q.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
