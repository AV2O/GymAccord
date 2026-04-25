<?php

namespace App\Controller;

use App\Repository\WorkshopsRepository;
use App\Repository\WorkshopsTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CoursCoController extends AbstractController
{
    #[Route('/coursCo', name: 'app_coursCo')]
    public function index(WorkshopsRepository $repo): Response
    {
        $allWorkshops    = $repo->findAll();
        $groupedWorkshops = [];

        foreach ($allWorkshops as $w) {
            $categoryName = $w->getWorkshopType()->getName();
            $sportName    = $w->getNameClass();

            if (!isset($groupedWorkshops[$categoryName])) {
                $groupedWorkshops[$categoryName] = [];
            }

            $dejaPresent = false;
            foreach ($groupedWorkshops[$categoryName] as $sportExistant) {
                if ($sportExistant->getNameClass() === $sportName) {
                    $dejaPresent = true;
                    break;
                }
            }

            if (!$dejaPresent) {
                $groupedWorkshops[$categoryName][] = $w;
            }
        }

        return $this->render('main/coursCo.html.twig', [
            'groupedWorkshops' => $groupedWorkshops,
        ]);
    }

    // ✅ requirements: ['id' => '\d+'] — LA CORRECTION CLÉ
    #[Route('/reservation/{id}', name: 'app_reservation_with_id', defaults: ['id' => null], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function reservation($id, WorkshopsRepository $repo, WorkshopsTypeRepository $repoType): Response
    {
        $workshops      = $repo->findAll();
        $workshopsArray = [];

        foreach ($workshops as $w) {
            $dateText = ($w->getDayClass() === 'Sur RDV')
                ? 'Sur RDV'
                : $w->getDayClass() . ' à ' . $w->getStartTime()->format('H:i');

            $placesRestantes = $w->getMaxCapacity() - count($w->getReservations());

            $workshopsArray[] = [
                'id'     => $w->getId(),
                'name'   => $w->getNameClass(),
                'typeId' => $w->getWorkshopType()->getId(),
                'label'  => $dateText . ' (' . $placesRestantes . ' places)',
            ];
        }

        return $this->render('main/reservation.html.twig', [
            'workshopsArray' => $workshopsArray,
            'selectedId'     => $id,
            'types'          => $repoType->findAll(),
        ]);
    }
}
