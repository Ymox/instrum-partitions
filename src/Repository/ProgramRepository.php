<?php

namespace App\Repository;

/**
 * ProgramRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProgramRepository extends \Doctrine\ORM\EntityRepository
{
    public function countAll()
    {
        $qb = $this->createQueryBuilder('p');
        $qb ->select($qb->expr()->count('p.id'));

        return $qb->getQuery()->getSingleScalarResult();
    }
}
