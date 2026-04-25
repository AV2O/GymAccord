<?php

namespace App\Repository;

use App\Entity\Workshops;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WorkshopsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshops::class);
    }

    // La version optimisée pour réduire les requêtes dans le Profiler
    public function findAllOptimized(): array
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.workshop_type', 't')
            ->addSelect('t')
            ->leftJoin('w.coach', 'c')
            ->addSelect('c')
            ->leftJoin('w.reservations', 'r')
            ->addSelect('r')
            ->getQuery()
            ->getResult();
    }

    public function findCalendarWorkshops(): array
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.workshop_type', 't')
            ->addSelect('t')
            ->leftJoin('w.coach', 'c')
            ->addSelect('c')
            ->leftJoin('w.reservations', 'r')
            ->addSelect('r')
            ->orderBy('w.start_time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllForDynamicPage(): array
    {
        return $this->createQueryBuilder('w')
            ->leftJoin('w.workshop_type', 't')
            ->addSelect('t')
            ->getQuery()
            ->getResult();
    }
}
