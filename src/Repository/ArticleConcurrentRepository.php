<?php

namespace App\Repository;

use App\Entity\ArticleConcurrent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Parameter;

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

    public function getArticlesConcurrentsWithEtats($etats, $article)
    {
        $parameters = [];
        $where = '(';
        $ind = 0;
        foreach($etats as $etat) {
            $ind++;
            $where .= 'a.etat = :etat'.strval($ind);
            $parameters[] = new Parameter('etat'.strval($ind) , $etat);

            if ($ind < count($etats)) {
                $where .= ' OR ';
            }
        }

        $where .= ')';

        // Article
        $where .= ' AND (a.article = :article)';
        $parameters[] = new Parameter('article', $article);

        $req = $this->createQueryBuilder('a')
            ->select()
            ->where($where)
            ->setParameters(new ArrayCollection($parameters))
            ->orderBy('a.prix', 'ASC')            
            ->getQuery()
            ->getResult()
        ;

        return $req;
    }
}
