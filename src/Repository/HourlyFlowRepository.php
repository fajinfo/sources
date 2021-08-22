<?php

namespace App\Repository;

use App\Entity\DailyFlow;
use App\Entity\HourlyFlow;
use App\Entity\Sources;
use \DateTime;
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

    /**
     * @param DateTime $dateTime
     * @param Sources $source
     * @return DailyFlow|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getForArchive(DateTime $dateTime, Sources $source){
        $qb = $this->createQueryBuilder('hf');

        $dateDebut = clone $dateTime;
        $dateDebut->setTime(0,0,0);
        $dateFin = clone $dateTime;
        $dateFin->setTime(23, 59, 59);

        $qb->select('MAX(hf.maximumFlowrate) AS max_flow, MIN(hf.minimumFlowrate) AS min_flow, AVG(hf.mediumFlowrate) AS avg_flow')
            ->andWhere('hf.source = :se')
            ->setParameter('se', $source)
            ->andWhere('hf.date BETWEEN :from AND :to')
            ->setParameter('from', $dateDebut)
            ->setParameter('to', $dateFin);

        $result = $qb->getQuery()->getScalarResult();

        if($result[0]['avg_flow'] != null){
            $dailyFlow = new DailyFlow();
            $dailyFlow->setDate($dateDebut);
            $dailyFlow->setMaximumFlowrate($result[0]['max_flow']);
            $dailyFlow->setMediumFlowrate($result[0]['avg_flow']);
            $dailyFlow->setMinimumFlowrate($result[0]['min_flow']);
            $dailyFlow->setSource($source);
            return $dailyFlow;
        }
        return null;
    }

    /**
     * @param DateTime $dateTime
     * @return int|mixed|string
     */
    public function removeArchivedMonth(DateTime $dateTime){
        $dateDebut = clone $dateTime;
        $dateDebut->setTime(0,0,0);
        $dateFin = new DateTime($dateDebut->format('Y-m-t').' 23:59:59');
        $qb = $this->createQueryBuilder('u')
            ->delete(HourlyFlow::class, 'hf')
            ->andWhere('hf.date BETWEEN :from and :to')
            ->setParameter('from', $dateDebut)
            ->setParameter('to', $dateFin)
        ;
        return $qb->getQuery()->execute();
    }
}
