<?php

namespace App\Controller;

use App\Document\Commentary;
use App\Entity\Users; 
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentaryController extends AbstractController
{
    #[Route('/commentaire/ajouter', name: 'app_comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')] // <--- Sécurité activée ici
    public function add(Request $request, DocumentManager $dm): Response 
    {
        // On récupère l'utilisateur (on sait qu'il existe grâce à IsGranted)
        /** @var Users $user */
        $user = $this->getUser();

        $contenu = $request->request->get('contenu');
        $longueur = $contenu ? mb_strlen($contenu) : 0;

        if ($longueur >= 5 && $longueur <= 150) {
            $commentaire = new Commentary();
            $commentaire->setContenu($contenu);
            $commentaire->setUserId($user->getId());
            $commentaire->setAuthorName($user->getLastName()); 

            $dm->persist($commentaire);
            $dm->flush();

            $this->addFlash('success', 'Votre commentaire a été publié !');
        } else {
            if ($longueur > 150) {
                $this->addFlash('danger', "Trop long ! ($longueur/150 caractères).");
            } else {
                $this->addFlash('danger', 'Le commentaire doit faire au moins 5 caractères.');
            }
        }

        return $this->redirectToRoute('app_main');
    }



    public function index(DocumentManagerInterface $dm): Response
    {
        // On utilise notre Repository personnalisé
        $commentaires = $dm->getRepository(CommentaryController::class)->findLastComments(5);

        return $this->render('main/index.html.twig', [
            'comments' => $commentaires,
        ]);
    }
}
