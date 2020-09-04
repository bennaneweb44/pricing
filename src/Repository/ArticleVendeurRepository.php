<?php

namespace App\Repository;

use App\Entity\ArticleVendeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ArticleVendeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleVendeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleVendeur[]    findAll()
 * @method ArticleVendeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleVendeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleVendeur::class);
    }

    // /**
    //  * @return ArticleVendeur[] Returns an array of ArticleVendeur objects
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
    public function findOneBySomeField($value): ?ArticleVendeur
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
