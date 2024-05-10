<?php

namespace App\Repository;

use App\Entity\Part;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class PartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Part::class);
    }

    public function save(Part $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Part $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findWithFile(?int $limit = null)
    {
        $qb = $this->getBaseQueryBuilder();
        $qb ->where($qb->expr()->isNotNull('p.file'));
        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findMissingFile(array $fileNames)
    {
        $qb = $this->getBaseQueryBuilder();
        $qb ->where($qb->expr()->notIn('p.file', ':files'))
            ->andWhere($qb->expr()->isNotNull('p.file'))
            ->setParameter(':files', $fileNames);

        return $qb->getQuery()->getResult();
    }

    public function getFiles(): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb ->select('PARTIAL p.{id,file}')
            ->where($qb->expr()->isNotNull('p.file'));
        
        return array_column($qb->getQuery()->getArrayResult(), 'file', 'id');
    }

    private function getBaseQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');
        $qb ->leftJoin('p.instrument', 'i')->addSelect('i')
            ->leftJoin('p.piece', 'pi')->addSelect('pi')
            ->orderBy('pi.id')
            ->addOrderBy('i.id')
            ->addOrderBy('p.number');

        return $qb;
    }
}
