<?php

namespace App\Controller;

use App\Repository\WorkshopsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(WorkshopsRepository $workshopsRepo): Response
    {
        return $this->render('main/calendar.html.twig', [
            // On récupère les données optimisées
            'workshops' => $workshopsRepo->findCalendarWorkshops(),
        ]);
    }
}