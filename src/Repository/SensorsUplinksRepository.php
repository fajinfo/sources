<?php

namespace App\Repository;

use App\Entity\HourlyFlow;
use App\Entity\Sources;
use \DateTime;
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

    /**
     * @param DateTime $dateTime
     * @param Sources $source
     * @return HourlyFlow
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getForArchive(DateTime $dateTime, Sources $source){
        $qb = $this->createQueryBuilder('u');

        $qb->leftJoin('u.sensor', 's')
            ->select('s', 'MAX(u.waterFlowRate) as max_flow, MIN(u.waterFlowRate) as min_flow, AVG(u.waterFlowRate) as avg_flow')
            ->andWhere('s.source = :se')
            ->setParameter('se', $source)
            ->andWhere('u.date BETWEEN :from AND :to')
            ->setParameter('from', $dateTime)
            ->setParameter('to', $dateTime->modify('+1 hour'));

        $result = $qb->getQuery()->getSingleScalarResult();

        $hourlyFlow = new HourlyFlow();
        $hourlyFlow->setDate($dateTime);
        $hourlyFlow->setMaximumFlowrate($result['max_flow']);
        $hourlyFlow->setMediumFlowrate($result['avg_flow']);
        $hourlyFlow->setMinimumFlowrate($result['min_flow']);
        $hourlyFlow->setSource($source);
        return new HourlyFlow();
    }

    /**
     * @param DateTime $dateTime
     * @param Sources $source
     * @return int|mixed|string
     */
    public function removeArchivedDay(DateTime $dateTime, Sources $source){
        $qb = $this->createQueryBuilder('u')
            ->delete('u')
            ->leftJoin('u.sensor', 's')
            ->andWhere('s.source = :source')
            ->setParameter('source', $source)
            ->andWhere('u.date BETWEEN :from and :to')
            ->setParameter('from', $dateTime->setTime(0, 0, 0))
            ->setParameter('to', $dateTime->setTime(23, 59, 59))
        ;
        return $qb->getQuery()->execute();
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
