<?php

namespace App\Repository;

use App\Entity\Sources;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sources|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sources|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sources[]    findAll()
 * @method Sources[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sources::class);
    }

    /**
     * @param User $user
     * @return Sources[]
     */
    public function findForDashboardWithData(User $user){
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.ViewUser', 'vu')
            ->andWhere('vu = :user')
            ->setParameter('user', $user);
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Sources[] Returns an array of Sources objects
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
    public function findOneBySomeField($value): ?Sources
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
