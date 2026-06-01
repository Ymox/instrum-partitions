<?php

namespace App\Repository;

use App\Entity\Instrument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class InstrumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instrument::class);
    }

    public function save(Instrument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Instrument $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginateBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null): Paginator
    {
        $qb = $this->createQueryBuilder('i');

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
            case 'key':
            case 'family':
            case 'common':
                $field = 'i.' . $field;
                break;
            default:
                $field = 'i.id';
                break;
        }

        return $field;
    }
}