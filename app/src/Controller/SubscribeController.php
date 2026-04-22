<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Take;
use App\Repository\SubscribesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SubscribeController extends AbstractController
{
    #[Route('/tarifs', name: 'app_tarifs')]
    public function index(SubscribesRepository $subscribesRepository): Response
    {
        return $this->render('main/tarifs.html.twig', [
            'abonnements' => $subscribesRepository->findAll(),
        ]);
    }

    #[Route('/tarifs/confirm/{id}', name: 'app_subscribe_confirm')]
    #[IsGranted('ROLE_USER')]
    public function confirm(int $id, SubscribesRepository $repo, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\Users $user */ 
        $user = $this->getUser();
        $abo = $repo->find($id);

        if ($user && $abo) {
            // 1. Désactivation de l'ancien forfait
            $oldSubscriptions = $em->getRepository(Take::class)->findBy([
                'user' => $user,
                'is_active' => true
            ]);

            foreach ($oldSubscriptions as $oldSub) {
                $em->remove($oldSub);
            }

            // 2. Nettoyage des réservations
            $reservations = $user->getReservations();
            
            // CORRECTION ICI : On utilise getNameSubscribe() au lieu de getName()
            $nomNouveauForfait = $abo->getNameSubscribe(); 

            foreach ($reservations as $res) {
                $workshop = $res->getWorkshop();
                // Vérifie aussi ici si c'est getName() ou getNameType() par exemple
                $typeWorkshop = $workshop->getWorkshopType()->getName(); 

                if ($nomNouveauForfait === 'Basic') {
                    $em->remove($res);
                } 
                elseif ($nomNouveauForfait === 'Premium') {
                    if ($typeWorkshop === 'Séance Perso') {
                        $em->remove($res);
                    }
                }
            }

            // 3. Nouveau forfait (Take)
            $take = new Take();
            $take->setUser($user);
            $take->setSubscribe($abo);
            $take->setIsActive(true);
            $take->setEndsAt(new \DateTimeImmutable('+1 year'));

            $em->persist($take);
            $em->flush();

            $this->addFlash('success', 'Votre forfait a été mis à jour !');
        }

        return $this->redirectToRoute('app_dashboard');
    }



    #[Route('/tarifs/cancel', name: 'app_subscribe_cancel')]
    public function cancel(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if ($user) {
            $activeTake = $em->getRepository(Take::class)->findOneBy([
                'user' => $user,
                'is_active' => true
            ]);

            if ($activeTake) {
                $em->remove($activeTake);
                $em->flush();
                $this->addFlash('success', 'Forfait résilié.');
            }
        }
        return $this->redirectToRoute('app_dashboard');
    }
}
