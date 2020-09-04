<?php

namespace App\Repository;

use App\Entity\Concurrent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Concurrent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concurrent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concurrent[]    findAll()
 * @method Concurrent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcurrentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concurrent::class);
    }

    // /**
    //  * @return Concurrent[] Returns an array of Concurrent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Concurrent
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
