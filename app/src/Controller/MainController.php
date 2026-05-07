<?php

namespace App\Controller;

use App\Document\Commentary;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ImageOptimizerService;

final class MainController extends AbstractController
{

    public function __construct(
        private ImageOptimizerService $imageOptimizer
    ) {}


    #[Route('/', name: 'app_main')]
    public function index(DocumentManager $dm): Response // <--- Ici aussi on enlève "Interface"
    {
        $commentaires = $dm->getRepository(Commentary::class)->findBy([], ['createdAt' => 'DESC'], 5);

        $photoDir = $this->getParameter('kernel.project_dir') . '/public/images/photo';
        $this->imageOptimizer->convertDirectory($photoDir);


        return $this->render('main/index.html.twig', [
            'comments' => $commentaires,
        ]);
    }

    #[Route('/solo', name: 'app_solo')]
    public function solo(): Response
    {
        return $this->render('main/solo.html.twig');
    }


    #[Route('/a-propos', name: 'app_a-propos')]
    public function aPropos(): Response
    {
        return $this->render('main/a-propos.html.twig');
    }

    #[Route('/mention-legal', name: 'app_mention-legal')]
    public function mentionLegal(): Response
    {
        return $this->render('main/mention-legal.html.twig');
    }
}
