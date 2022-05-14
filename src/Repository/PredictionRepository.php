<?php

namespace App\Repository;

use App\Entity\Prediction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prediction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prediction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prediction[]    findAll()
 * @method Prediction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PredictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prediction::class);
    }

    // /**
    //  * @return Prediction[] Returns an array of Prediction objects
    //  */
    
    public function findPodium($eventId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.event = :val')
            ->andWhere('p.score != :null')->setParameter('null', serialize(null))
            ->setParameter('val', $eventId)
            ->orderBy('p.score', 'DESC')
            ->addOrderBy('p.updated_at', 'ASC')
            ->addOrderBy('p.created_at', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findExistingPredictionType($event, $user)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.event = :event')
            ->andWhere('p.user = :user')
            ->andWhere('p.pole != :null')->setParameter('null', serialize(null))
            ->setParameter('event', $event)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getPredictionCount($eventId)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.event = :val')
            ->setParameter('val', $eventId)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getUserPredictionCount($user)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :val')
            ->setParameter('val', $user)
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Prediction
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
