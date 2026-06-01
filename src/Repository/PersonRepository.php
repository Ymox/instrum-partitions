<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function save(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginateBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null): Paginator
    {
        $qb = $this->createQueryBuilder('p');

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
            case 'firstName':
            case 'lastName':
                $field = 'p.' . $field;
                break;
            default:
                $field = 'p.id';
                break;
        }

        return $field;
    }
}
