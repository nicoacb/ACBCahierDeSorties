<?php

namespace Aviron\SortieBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * SortieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SortieRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSortiesTerminees()
    {
        return $this->createQueryBuilder('s')
            ->where('s.hretour IS NOT NULL')
            ->orderBy('s.date')
            ->addOrderBy('s.hdepart')
            ->getQuery()
            ->getResult();
    }

    public function getSortiesTerminees($page, $nbParPage)
    {
        $query = $this->createQueryBuilder('s')
                    ->where('s.hretour IS NOT NULL')
                    ->orderBy('s.date', 'DESC')
                    ->addOrderBy('s.hretour', 'DESC')
                    ->setFirstResult(($page-1)*$nbParPage)
                    ->setMaxResults($nbParPage);

        return new Paginator($query, true);
    }
}
