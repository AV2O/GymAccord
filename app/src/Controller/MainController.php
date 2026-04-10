<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/tarifs', name: 'app_tarifs')]
    public function tarifs(): Response
    {
        return $this->render('main/tarifs.html.twig');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('main/dashboard.html.twig');
    }

    #[Route('/a-propos', name: 'app_a-propos')]
    public function aPropos(): Response
    {
        return $this->render('main/a-propos.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('main/login.html.twig');
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('main/register.html.twig');
    }

    #[Route('/reservation', name: 'app_reservation')]
    public function reservation(): Response
    {
        return $this->render('main/reservation.html.twig');
    }

}
