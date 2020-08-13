<?php

namespace App\Repository;

use App\Entity\FrequencePratique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FrequencePratique|null find($id, $lockMode = null, $lockVersion = null)
 * @method FrequencePratique|null findOneBy(array $criteria, array $orderBy = null)
 * @method FrequencePratique[]    findAll()
 * @method FrequencePratique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrequencePratiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FrequencePratique::class);
    }

    // /**
    //  * @return FrequencePratique[] Returns an array of FrequencePratique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FrequencePratique
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
