<?php

namespace App\Repository;

use App\Entity\EngagementAssociation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EngagementAssociation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EngagementAssociation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EngagementAssociation[]    findAll()
 * @method EngagementAssociation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EngagementAssociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EngagementAssociation::class);
    }

    // /**
    //  * @return EngagementAssociation[] Returns an array of EngagementAssociation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EngagementAssociation
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
