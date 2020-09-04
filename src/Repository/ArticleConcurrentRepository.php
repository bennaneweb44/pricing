<?php

namespace App\Repository;

use App\Entity\ArticleConcurrent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ArticleConcurrent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleConcurrent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleConcurrent[]    findAll()
 * @method ArticleConcurrent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleConcurrentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleConcurrent::class);
    }

    // /**
    //  * @return ArticleConcurrent[] Returns an array of ArticleConcurrent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticleConcurrent
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
