<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/coursCo', name: 'app_coursCo')]
    public function coursCo(): Response
    {
        return $this->render('main/coursCo.html.twig');
    }

    #[Route('/solo', name: 'app_solo')]
    public function solo(): Response
    {
        return $this->render('main/solo.html.twig');
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function calendar(): Response
    {
        return $this->render('main/calendar.html.twig');
    }


    #[Route('/a-propos', name: 'app_a-propos')]
    public function aPropos(): Response
    {
        return $this->render('main/a-propos.html.twig');
    }

    
}
