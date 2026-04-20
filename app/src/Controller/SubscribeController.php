<?php

namespace App\Controller;

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
    public function confirm(int $id, SubscribesRepository $repo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $abo = $repo->find($id);

        if ($user && $abo) {
            // 1. DEJÀ un forfait actif pour cet utilisateur ?
            $oldSubscriptions = $em->getRepository(Take::class)->findBy([
                'user' => $user,
                'is_active' => true
            ]);

            // 2. On les désactive (ou on les supprime) avant de mettre le nouveau
            foreach ($oldSubscriptions as $oldSub) {
                // Option A : suppression
                $em->remove($oldSub);

                // Option B : desactive
                // $oldSub->setIsActive(false);
            }

            // 3. NOUVEAU forfait creer
            $take = new Take();
            $take->setUser($user);
            $take->setSubscribe($abo);
            $take->setIsActive(true);
            $take->setEndsAt(new \DateTimeImmutable('+1 year'));

            $em->persist($take);

            // enregistre tout en une seule fois
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
