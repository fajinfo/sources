<?php

namespace App\Repository;

use App\Entity\Sensors;
use App\Entity\SensorsUplinks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SensorsUplinks|null find($id, $lockMode = null, $lockVersion = null)
 * @method SensorsUplinks|null findOneBy(array $criteria, array $orderBy = null)
 * @method SensorsUplinks[]    findAll()
 * @method SensorsUplinks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensorsUplinksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SensorsUplinks::class);
    }

    public static function queryBuilderForSensorsTable(SensorsUplinksRepository $r): QueryBuilder{
        return $r->createQueryBuilder('s')
            ->orderBy('s.date', 'desc');
    }

    // /**
    //  * @return SensorsUplinks[] Returns an array of SensorsUplinks objects
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
    public function findOneBySomeField($value): ?SensorsUplinks
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
