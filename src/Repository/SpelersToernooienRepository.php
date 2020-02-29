<?php

namespace App\Repository;

use App\Entity\SpelersToernooien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SpelersToernooien|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpelersToernooien|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpelersToernooien[]    findAll()
 * @method SpelersToernooien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpelersToernooienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpelersToernooien::class);
    }

    // /**
    //  * @return SpelersToernooien[] Returns an array of SpelersToernooien objects
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
    public function findOneBySomeField($value): ?SpelersToernooien
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
