<?php

namespace Aviron\UserBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCanRowQueryBuilder()
    {
        return $this
            ->createQueryBuilder('u')
            ->orderBy('u.prenom')
            ->addOrderBy('u.nom');
    }

    public function getMembres($page, $nbParPage)
    {
        $query = $this->createQueryBuilder('u')
                    ->orderBy('u.prenom')
                    ->addOrderBy('u.nom')
                    ->setFirstResult(($page-1)*$nbParPage)
                    ->setMaxResults($nbParPage);

        return new Paginator($query, true);
    }

    public function findLikePrenomNom($q)
    {
        return $this->createQueryBuilder('u')
            ->where('u.prenom LIKE :query')
            ->setParameter(':query', $q)
            ->getQuery()
            ->getResult();
    }
}
