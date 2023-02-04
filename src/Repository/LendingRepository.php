<?php

namespace App\Repository;

use App\Entity\Lending;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class LendingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lending::class);
    }

    public function save(Lending $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lending $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): Paginator
    {
        $qb = $this->createQueryBuilder('l');
        $qb ->leftJoin('l.band', 'b')->addSelect('b')
            ->leftJoin('l.pieces', 'p')->addSelect('p');

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

    private function sanitizeField(string $field): string
    {
        switch ($field) {
            case 'band':
                $field = 'b.name';
                break;
            case 'piece':
                $field = 'p.name';
                break;
            case 'contact':
                $field = 'l.contact';
                break;
            case 'start':
            case 'id':
                $field = 'p.' . $field;
            default:
                break;
        }

        return $field;
    }
}
