<?php

namespace App\Repository;

use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    /**
     * @return Score[] Returns an array of Score objects
     */
    
    public function findTopTen()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.total', 'DESC')
            ->addOrderBy('s.eventWins', 'DESC')
            ->addOrderBy('s.eventSecond', 'DESC')
            ->addOrderBy('s.eventThird', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Returns count of active users in the ranking table for the current season.
     * Considered active if placed at least one prediction.
     *
     * @param string|int $season
     * @return string Number of users in ranking
     */
    public function getUsersCount($season)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.season = :val')
            ->setParameter('val', $season)
            ->select('count(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getUserGlobalRanking()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.total', 'DESC')
            ->select('identity(s.user)')
            ->getQuery()
            ->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Score
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
