<?php

namespace YSoft\InstrumBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PieceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PieceRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     *
     * @param array $criteria
     * @param array $orderBy
     * @param integer $limit
     * @param integer $offset
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function searchBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('p');
        $qb ->leftJoin('p.work', 'w')
            ->leftJoin('p.composers', 'c')->addSelect('c')
            ->leftJoin('p.arrangers', 'a')->addSelect('a')
            ->leftJoin('p.publisher', 'pu')->addSelect('pu')
            ->leftJoin('p.concerts', 's')->addSelect('s')->setMaxResults(1)
            ->where($qb->expr()->isNull('w.id'));

        foreach ($criteria as $field => $value) {
            if (empty($value)) {
                continue;
            }
            $marker = ':' . $field;
            $qb->andWhere($qb->expr()->like($this->sanitizeField($field), $marker));
            if ($field == 'name') {
                $qb->orWhere($qb->expr()->like('p.translation', $marker));
            }
            $qb->setParameter($marker, '%' . $value . '%');
        }

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy($this->sanitizeField($field), $direction);
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

    /**
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @return array
     */
    public function findForSuisa(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        $qb = $this->createQueryBuilder('p');
        $qb ->addSelect($qb->expr()->count('c.id'))
            ->innerJoin('p.concerts', 'c')->addSelect('c')
            ->innerJoin('p.composers', 'cp')->addSelect('cp')
            ->innerJoin('p.arrangers', 'ar')->addSelect('ar')
            ->where($qb->expr()->between('c.date', ':start', ':end'))
            ->setParameter(':start', $start)
            ->setParameter(':end', $end)
            ->groupBy('p.id')
            ->orderBy($qb->expr()->asc('c.date'))
        ;

        return $qb->getQuery()->getResult();
    }

    private function sanitizeField($field)
    {
        switch ($field) {
            case 'publisher':
                $field = 'pu.name';
                break;
            case 'arranger':
                $field = 'a.lastName';
                break;
            case 'composer':
                $field = 'c.lastName';
                break;
            case 'year':
            case 'name':
            case 'translation':
            case 'reference':
            case 'id':
                $field = 'p.' . $field;
            default:
                break;
        }

        return $field;
    }
}
