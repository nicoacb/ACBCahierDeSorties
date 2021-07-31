<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ReservationRepository
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function DonneReservations($utilisateur)
    {
        $qb = $this
            ->createQueryBuilder('r')
            ->where('r.idut = :idut')
            ->andWhere('r.datesupp is null')
            ->setParameter('idut', $utilisateur);

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function CompteNombreDeReservations($identrainement)
    {
        $qb = $this
            ->createQueryBuilder('r')
            ->select('COUNT(r)')
            ->where('r.identrainement = :identrainement')
            ->andWhere('r.datesupp is null')
            ->setParameter('identrainement', $identrainement);

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}
