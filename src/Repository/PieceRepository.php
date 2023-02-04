<?php

namespace App\Repository;

use App\Entity\Piece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class PieceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Piece::class);
    }

    public function save(Piece $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Piece $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): \Doctrine\ORM\Tools\Pagination\Paginator
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

    public function findForSuisa(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb ->addSelect($qb->expr()->countDistinct('c.id'))
            ->innerJoin('p.concerts', 'c')->addSelect('c')
            ->innerJoin('p.composers', 'cp')->addSelect('cp')
            ->leftJoin('p.arrangers', 'ar')->addSelect('ar')
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
