<?php

namespace App\Repository;

use App\Entity\Etat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Parameter;

/**
 * @method Etat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etat[]    findAll()
 * @method Etat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etat::class);
    }

    // /**
    //  * @return Etat[] Returns an array of Etat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etat
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByIntitules($intitules)
    {
        $parameters = [];
        $where = '';
        $ind = 0;
        foreach($intitules as $intitutle) {
            $ind++;
            $where .= 'a.intitule = :etat'.strval($ind);
            $parameters[] = new Parameter('etat'.strval($ind) , $intitutle);

            if ($ind < count($intitules)) {
                $where .= ' OR ';
            }
        }

        $req = $this->createQueryBuilder('a')
            ->select()
            ->where($where)
            ->setParameters(new ArrayCollection($parameters))
            ->orderBy('a.id', 'ASC')            
            ->getQuery()
            ->getResult()
        ;
        
        return $req;
    }
}
