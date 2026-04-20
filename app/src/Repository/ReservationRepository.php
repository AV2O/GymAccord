<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Récupère les réservations avec les workshops et images en une seule requête
     */
    public function findUserReservationsOptimized($user): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.workshop', 'w') // On lie le workshop
            ->addSelect('w')              // On demande à SQL de prendre les colonnes du workshop
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}