<?php

namespace App\Controller;

use App\Entity\Take;
use App\Entity\Reservation;
use App\Repository\SubscribesRepository;
use App\Repository\WorkshopsRepository;
use App\Repository\WorkshopsTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WorkshopsController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    #[IsGranted('ROLE_USER')]
    public function index(WorkshopsRepository $workshopsRepo, WorkshopsTypeRepository $typesRepo): Response
    {
        $types = $typesRepo->findBy([], ['id' => 'ASC']);
        $workshops = $workshopsRepo->findAllOptimized();

        $workshopsArray = [];
        foreach ($workshops as $workshop) {
            $dateText = ($workshop->getDayClass() === 'Sur RDV')
                ? 'Sur RDV'
                : $workshop->getDayClass() . ' à ' . $workshop->getStartTime()->format('H:i');

            $placesRestantes = $workshop->getMaxCapacity() - count($workshop->getReservations());

            $workshopsArray[] = [
                'id' => $workshop->getId(),
                'typeId' => $workshop->getWorkshopType()->getId(),
                'name' => $workshop->getNameClass(),
                'label' => $dateText . " (" . $placesRestantes . " places)"
            ];
        }

        return $this->render('main/reservation.html.twig', [
            'types' => $types,
            'workshopsArray' => $workshopsArray,
        ]);
    }

    #[Route('/reservation/submit', name: 'app_reservation_submit', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserveSubmit(Request $request, WorkshopsRepository $repo, EntityManagerInterface $em): Response
    {
        $workshopId = $request->request->get('workshop_id');
        $workshop = $repo->find($workshopId);
        
        /** @var \App\Entity\Users $user */
        $user = $this->getUser();

        if (!$user || !$workshop) {
            $this->addFlash('error', 'Séance ou utilisateur introuvable.');
            return $this->redirectToRoute('app_reservation');
        }

        $activeSubscribe = null;
        foreach ($user->getTakes() as $take) {
            if ($take->isActive()) {
                $activeSubscribe = $take->getSubscribe();
                break;
            }
        }

        if (!$activeSubscribe) {
            $this->addFlash('danger', 'Vous n\'avez pas d\'abonnement actif.');
            return $this->redirectToRoute('app_reservation');
        }

        $nomAbo = strtoupper($activeSubscribe->getNameSubscribe());
        $nomTypeCours = strtoupper($workshop->getWorkshopType()->getName());

        if (str_contains($nomAbo, 'BASIC')) {
            $this->addFlash('danger', 'L\'abonnement BASIC ne permet pas de réserver.');
            return $this->redirectToRoute('app_reservation');
        }

        if (str_contains($nomTypeCours, 'PERSO') && !str_contains($nomAbo, 'GOLD')) {
            $this->addFlash('danger', 'Les coachings personnels sont réservés aux membres GOLD.');
            return $this->redirectToRoute('app_reservation');
        }

        $reservation = new Reservation();
        $reservation->setWorkshop($workshop);
        $reservation->setUser($user);
        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Ta place est réservée !');
        return $this->redirectToRoute('app_dashboard');
    }

    /**
     * C'EST CETTE ROUTE QUE TON DASHBOARD CHERCHE
     */
    #[Route('/reservation/annuler/{id}', name: 'app_reservation_delete', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Action non autorisée.");
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Séance annulée.');
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/tarifs/confirm/{id}', name: 'app_subscribe_confirm')]
    #[IsGranted('ROLE_USER')]
    public function confirm(int $id, SubscribesRepository $repo, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\Users $user */ 
        $user = $this->getUser();
        $abo = $repo->find($id);

        if ($user && $abo) {
            $oldTakes = $em->getRepository(Take::class)->findBy(['user' => $user, 'is_active' => true]);
            foreach ($oldTakes as $old) { $em->remove($old); }

            $nomNouveauForfait = strtoupper($abo->getNameSubscribe());

            foreach ($user->getReservations() as $res) {
                $typeWorkshop = strtoupper($res->getWorkshop()->getWorkshopType()->getName());

                if (str_contains($nomNouveauForfait, 'BASIC')) {
                    $em->remove($res);
                } elseif (str_contains($nomNouveauForfait, 'PREMIUM') && str_contains($typeWorkshop, 'PERSO')) {
                    $em->remove($res);
                }
            }

            $take = new Take();
            $take->setUser($user);
            $take->setSubscribe($abo);
            $take->setIsActive(true);
            $take->setEndsAt(new \DateTimeImmutable('+1 year'));

            $em->persist($take);
            $em->flush();

            $this->addFlash('success', 'Forfait mis à jour !');
        }

        return $this->redirectToRoute('app_dashboard');
    }
}