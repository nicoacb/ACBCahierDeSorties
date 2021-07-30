<?php

namespace App\Repository;

use App\Entity\TypeBateau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * TypeBateauRepository
 */
class TypeBateauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeBateau::class);
    }

    public function getListeTypesBateau()
    {
        return $this->getListeTypesBateauQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function getListeTypesBateauQueryBuilder()
    {
        return $this
            ->createQueryBuilder('t')
            ->orderBy('t.nom');
    }
}
