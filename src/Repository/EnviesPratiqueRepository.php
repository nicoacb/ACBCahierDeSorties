<?php

namespace App\Repository;

use App\Entity\EnviesPratique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EnviesPratique|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnviesPratique|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnviesPratique[]    findAll()
 * @method EnviesPratique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnviesPratiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnviesPratique::class);
    }

    // /**
    //  * @return EnviesPratique[] Returns an array of EnviesPratique objects
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
    public function findOneBySomeField($value): ?EnviesPratique
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
