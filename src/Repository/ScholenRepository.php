<?php

namespace App\Repository;

use App\Entity\Scholen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Scholen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scholen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scholen[]    findAll()
 * @method Scholen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScholenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scholen::class);
    }

    // /**
    //  * @return Scholen[] Returns an array of Scholen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Scholen
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
