<?php

namespace App\Repository;

use App\Entity\Entrainement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * EntrainementRepository
 */
class EntrainementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entrainement::class);
    }

    public function DonneEntrainementsPasses()
    {
        $qb = $this
            ->createQueryBuilder('e')
            ->where('e.datedebut < :aujourdhui')
            ->andWhere('e.datesupp is null')
            ->orderBy('e.datedebut', 'ASC')
            ->addOrderBy('e.heuredebut')
            ->setParameter('aujourdhui', date("Y-m-d", time()));

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function DonneEntrainementsAVenir()
    {
        $qb = $this
            ->createQueryBuilder('e')
            ->where('e.datedebut >= :aujourdhui')
            ->andWhere('e.datesupp is null')
            ->orderBy('e.datedebut', 'ASC')
            ->addOrderBy('e.heuredebut')
            ->setParameter('aujourdhui', date("Y-m-d", time()));

        return $qb
            ->getQuery()
            ->getResult();
    }
}
