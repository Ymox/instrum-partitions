<?php

namespace App\Repository;

use App\Entity\Size;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SizeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Size::class);
    }

    public function save(Size $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Size $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByDimension($width, $height)
    {
        $qb = $this->createQueryBuilder('s');
        $qb ->where($qb->expr()->between(':width', 's.minWidth', 's.maxWidth'))
            ->andWhere($qb->expr()->between(':height', 's.minHeight', 's.maxHeight'))
            ->setParameters(['width' => $width, 'height' => $height]);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
