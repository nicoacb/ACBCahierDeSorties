<?php

namespace App\Repository;

use App\Entity\MembreLicences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MembreLicences|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembreLicences|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembreLicences[]    findAll()
 * @method MembreLicences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreLicencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembreLicences::class);
    }

    public function DonnePreinscriptions()
    {
        return $this
            ->createQueryBuilder('l')
            ->join('l.membre', 'u')
            ->where('u.datesupp is null')
            ->andWhere('l.dateValidationInscription is null')
            ->andWhere('l.dateSuppressionInscription is null')
            ->orderBy('u.prenom')
            ->addOrderBy('u.nom')
            ->getQuery()
            ->getResult();
    }

    public function DonneLicencesASaisir()
    {
        return $this
            ->createQueryBuilder('l')
            ->join('l.membre', 'u')
            ->where('u.datesupp is null')
            ->andWhere('l.dateValidationInscription is not null')
            ->andWhere('l.dateSaisieLicence is null')
            ->andWhere('l.dateSuppressionInscription is null')
            ->orderBy('u.prenom')
            ->addOrderBy('u.nom')
            ->getQuery()
            ->getResult();
    }
}
