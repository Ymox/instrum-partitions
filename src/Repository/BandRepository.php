<?php

namespace App\Repository;

use App\Entity\Band;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class BandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Band::class);
    }

    public function save(Band $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Band $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginateBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null): Paginator
    {
        $qb = $this->createQueryBuilder('b');

        $i = 0;
        foreach ($criteria as $field => $value) {
            if (empty($value)) {
                continue;
            }
            $marker = ':params_' . $i++;
            $qb->andWhere($qb->expr()->like($this->sanitizeField($field), $marker));
            $qb->setParameter($marker, '%' . $value . '%');
        }

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy($this->sanitizeField($field), (strtolower($direction) === 'desc') ? 'desc' : 'asc');
        }

        if (null != $limit) {
            $qb->setMaxResults(max($limit, 0));
        }
        if (null != $offset) {
            $qb->setFirstResult(max($offset, 0));
        }

        $paginator = new Paginator($qb);

        return $paginator;
    }

    private function sanitizeField(string $field)
    {
        switch ($field) {
            case 'name':
                $field = 'b.' . $field;
                break;
            default:
                $field = 'b.id';
                break;
        }

        return $field;
    }
}
