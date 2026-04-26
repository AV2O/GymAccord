<?php

namespace App\Controller;

use App\Repository\WorkshopsTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SoloController extends AbstractController
{
    #[Route('/solo', name: 'app_solo')]
    public function solo(WorkshopsTypeRepository $repoType): Response
    {
        $typePerso = $repoType->findByNameContaining('perso');

        return $this->render('main/solo.html.twig', [
            'typePersoId' => $typePerso ? $typePerso->getId() : null,
        ]);
    }
}
