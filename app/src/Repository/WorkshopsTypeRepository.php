<?php

namespace App\Repository;

use App\Entity\WorkshopsType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkshopsType>
 */
class WorkshopsTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkshopsType::class);
    }

    public function findByNameContaining(string $keyword): ?WorkshopsType
    {
        return $this->createQueryBuilder('t')
            ->where('LOWER(t.name) LIKE :keyword')
            ->setParameter('keyword', '%' . strtolower($keyword) . '%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
