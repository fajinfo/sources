<?php

namespace App\Repository;

use App\Entity\DailyFlow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DailyFlow|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyFlow|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyFlow[]    findAll()
 * @method DailyFlow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyFlowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyFlow::class);
    }

    // /**
    //  * @return DailyFlow[] Returns an array of DailyFlow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DailyFlow
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
