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

    public function findAllForJs(): array
    {
        // On utilise la méthode optimisée (attention au nom ici)
        $workshops = $this->findAllOptimized();
        $data = [];

        foreach ($workshops as $workshop) {
            // Logique du label (ce qui sera affiché dans le select)
            $dateText = ($workshop->getDayClass() === 'Sur RDV') 
                ? 'Sur RDV' 
                : $workshop->getDayClass() . ' à ' . $workshop->getStartTime()->format('H:i');
            
            $placesRestantes = $workshop->getMaxCapacity() - count($workshop->getReservations());

            $data[] = [
                'id' => $workshop->getId(),
                'typeId' => $workshop->getWorkshopType()->getId(),
                'name' => $workshop->getNameClass(),
                'label' => $dateText . " (" . $placesRestantes . " places)"
            ];
        }
        return $data;
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
}