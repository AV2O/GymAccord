<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserProfileType;
use App\Repository\ReservationRepository;
use App\Repository\TakeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        ReservationRepository $resRepo, 
        TakeRepository $takeRepo
    ): Response {
        /** @var Users $user */
        $user = $this->getUser();

        // 1. Calcul de l'ancienneté (Pas de requête SQL ici, c'est du calcul PHP)
        $now = new \DateTime();
        $membershipDays = $user->getCreatedAt() ? $now->diff($user->getCreatedAt())->days : 0;

        // 2. Abonnement actif (Optimisé avec JOIN pour éviter une requête dans Twig)
        $activeSubscription = $takeRepo->findActiveSubscriptionFull($user);

        // 3. Réservations (Optimisé avec JOIN via ton Repository)
        $userReservations = $resRepo->findUserReservationsOptimized($user);

        // 4. Formulaire de photo
        $form = $this->createForm(UserProfileType::class, $user, [
            'action' => $this->generateUrl('app_profile_update_photo'),
            'method' => 'POST',
        ]);

        return $this->render('main/dashboard.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'activeSubscription' => $activeSubscription,
            'userReservations' => $userReservations,
            'membershipDays' => $membershipDays,
        ]);
    }
}