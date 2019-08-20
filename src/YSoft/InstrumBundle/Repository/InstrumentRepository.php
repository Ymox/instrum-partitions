<?php

namespace YSoft\InstrumBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * InstrumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InstrumentRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     *
     * @param array $criteria
     * @param array $orderBy
     * @param integer $limit
     * @param integer $offset
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function paginateBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('p');

        foreach ($criteria as $field => $value) {
            if (empty($value)) {
                continue;
            }
            $marker = ':' . $field;
            $qb->andWhere($qb->expr()->like('i.' . $field, $marker));
            $qb->setParameter($marker, '%' . $value . '%');
        }

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy('p.' . $field, $direction);
        }

        if (null != $limit) {
            $qb->setMaxResults($limit);
        }
        if (null != $offset) {
            $qb->setFirstResult($offset);
        }

        $paginator = new Paginator($qb);

        return $paginator;
    }
}