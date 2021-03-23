<?php

namespace App\Repository;

use App\Entity\MovieDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovieDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieDate[]    findAll()
 * @method MovieDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieDate::class);
    }

    // /**
    //  * @return MovieDate[] Returns an array of MovieDate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MovieDate
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
