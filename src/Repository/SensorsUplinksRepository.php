<?php

namespace App\Repository;

use App\Entity\HourlyFlow;
use App\Entity\Sources;
use \DateTime;
use App\Entity\SensorsUplinks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return HourlyFlow|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getForArchive(DateTime $dateTime, Sources $source){
        $qb = $this->createQueryBuilder('u');
        $dateDebut = clone $dateTime;
        $dateFin = clone $dateTime;
        $dateFin->modify('+59 minutes');

        $qb->leftJoin('u.sensor', 's')
            ->select('MAX(u.waterFlowRate) AS max_flow, MIN(u.waterFlowRate) AS min_flow, AVG(u.waterFlowRate) AS avg_flow')
            ->andWhere('s.source = :se')
            ->setParameter('se', $source)
            ->andWhere('u.waterFlowRate IS NOT NULL')
            ->andWhere('u.date BETWEEN :from AND :to')
            ->setParameter('from', $dateDebut)
            ->setParameter('to', $dateFin);

        $result = $qb->getQuery()->getScalarResult();

        if($result[0]['avg_flow'] != null){
            $hourlyFlow = new HourlyFlow();
            $hourlyFlow->setDate($dateDebut);
            $hourlyFlow->setMaximumFlowrate($result[0]['max_flow']);
            $hourlyFlow->setMediumFlowrate($result[0]['avg_flow']);
            $hourlyFlow->setMinimumFlowrate($result[0]['min_flow']);
            $hourlyFlow->setSource($source);
            return $hourlyFlow;
        }
        return null;
    }

    /**
     * @param DateTime $dateTime
     * @return int|mixed|string
     */
    public function removeArchivedDay(DateTime $dateTime){
        $dateDebut = clone $dateTime;
        $dateDebut->setTime(0,0,0);
        $dateFin = clone $dateTime;
        $dateFin->setTime(23, 59, 59);
        $qb = $this->createQueryBuilder('u')
            ->delete(SensorsUplinks::class, 'u')
            ->andWhere('u.date BETWEEN :from and :to')
            ->setParameter('from', $dateDebut)
            ->setParameter('to', $dateFin)
        ;
        return $qb->getQuery()->execute();
    }
}
