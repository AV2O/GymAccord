<?php

namespace App\Repository;

use App\Entity\Take;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Take::class);
    }

    /**
     * Récupère l'abonnement actif avec les détails du forfait joint
     */
    public function findActiveSubscriptionFull($user): ?Take
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.subscribe', 's') // Jointure avec l'entité Subscribe
            ->addSelect('s')               // On sélectionne les données de Subscribe
            ->where('t.user = :user')
            ->andWhere('t.is_active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}