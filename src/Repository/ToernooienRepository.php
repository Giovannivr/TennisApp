<?php

namespace App\Repository;

use App\Entity\Toernooien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Toernooien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Toernooien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Toernooien[]    findAll()
 * @method Toernooien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToernooienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Toernooien::class);
    }

    // /**
    //  * @return Toernooien[] Returns an array of Toernooien objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Toernooien
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
