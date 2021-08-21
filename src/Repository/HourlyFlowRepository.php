<?php

namespace App\Repository;

use App\Entity\HourlyFlow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HourlyFlow|null find($id, $lockMode = null, $lockVersion = null)
 * @method HourlyFlow|null findOneBy(array $criteria, array $orderBy = null)
 * @method HourlyFlow[]    findAll()
 * @method HourlyFlow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HourlyFlowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HourlyFlow::class);
    }

    // /**
    //  * @return HourlyFlow[] Returns an array of HourlyFlow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HourlyFlow
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
