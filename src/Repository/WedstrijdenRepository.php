<?php

namespace App\Repository;

use App\Entity\Wedstrijden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Wedstrijden|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wedstrijden|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wedstrijden[]    findAll()
 * @method Wedstrijden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WedstrijdenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wedstrijden::class);
    }

    // /**
    //  * @return Wedstrijden[] Returns an array of Wedstrijden objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wedstrijden
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
