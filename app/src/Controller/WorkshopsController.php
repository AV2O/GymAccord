<?php

namespace App\Entity; // Pour que l'IDE reconnaisse Users
namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Users;
use App\Repository\WorkshopsRepository;
use App\Repository\WorkshopsTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkshopsController extends AbstractController
{
    /**
     * Affiche la liste des séances et les types (utilisé par ton JS de filtrage)
     */
    #[Route('/reservation', name: 'app_reservation')]
    public function index(WorkshopsRepository $workshopsRepo, WorkshopsTypeRepository $typesRepo): Response
    {
        // On récupère TOUTES les catégories triées par ID
        $types = $typesRepo->findBy([], ['id' => 'ASC']);
        $workshops = $workshopsRepo->findAll();

        return $this->render('main/reservation.html.twig', [
            'workshops' => $workshops,
            'types' => $types,
        ]);
    }

    #[Route('/reservation/submit', name: 'app_reservation_submit', methods: ['POST'])]
    public function reserveSubmit(Request $request, WorkshopsRepository $repo, EntityManagerInterface $em): Response
    {
        $workshopId = $request->request->get('workshop_id');
        $workshop = $repo->find($workshopId);
        /** @var Users $user */
        $user = $this->getUser();

        if (!$user || !$workshop) {
            $this->addFlash('error', 'Séance ou utilisateur introuvable.');
            return $this->redirectToRoute('app_reservation');
        }

        // 1. RÉCUPÉRATION DE L'ABONNEMENT ACTIF
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
        // D'après ton image, le type ID 16 s'appelle "Séance perso"
        $nomTypeCours = strtoupper($workshop->getWorkshopType()->getName());

        // 2. BLOCAGE FORFAIT BASIC
        if (str_contains($nomAbo, 'BASIC')) {
            $this->addFlash('danger', 'Désolé, l\'abonnement BASIC ne permet pas de réserver des séances.');
            return $this->redirectToRoute('app_reservation');
        }

        // 3. SÉCURITÉ GOLD POUR LES "SÉANCE PERSO" (ID 16)
        if (str_contains($nomTypeCours, 'PERSO') || $workshop->getWorkshopType()->getId() === 16) {
            if (!str_contains($nomAbo, 'GOLD')) {
                $this->addFlash('danger', 'Les coachings personnels sont réservés exclusivement aux membres GOLD.');
                return $this->redirectToRoute('app_reservation');
            }
        }

        // 4. VÉRIFICATION DOUBLON
        foreach ($workshop->getReservations() as $res) {
            if ($res->getUser() === $user) {
                $this->addFlash('warning', 'Vous êtes déjà inscrit à ce cours.');
                return $this->redirectToRoute('app_reservation');
            }
        }

        $reservation = new Reservation();
        $reservation->setWorkshop($workshop);
        $reservation->setUser($user);
        $em->persist($reservation);
        $em->flush();

        $this->addFlash('success', 'Félicitations ! Ta place est réservée.');
        return $this->redirectToRoute('app_dashboard');
    }

    /**
     * Annulation d'une réservation (Accepte GET et POST pour éviter l'erreur de route)
     */
    #[Route('/reservation/annuler/{id}', name: 'app_reservation_delete', methods: ['GET', 'POST'])]
    public function delete(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        // Sécurité : on vérifie que la réservation appartient bien à l'utilisateur connecté
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Ce n'est pas votre réservation !");
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Séance annulée avec succès.');
        return $this->redirectToRoute('app_dashboard');
    }
}
