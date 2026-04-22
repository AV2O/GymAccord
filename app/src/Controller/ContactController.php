<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact/send', name: 'app_contact_send', methods: ['POST'])]
    public function send(Request $request, MailerInterface $mailer): Response
    {
        // 1. Récupération des données
        $nom = $request->request->get('nom');
        $userEmail = $request->request->get('email');
        $messageContent = $request->request->get('message');
        $consentement = $request->request->get('consentement'); // La checkbox

        // 2. VERIFICATION (La sécurité Back-end)
        if (!$nom || !$userEmail || !$messageContent || !$consentement) {
            $this->addFlash('danger', 'Tous les champs sont obligatoires, y compris le consentement.');
            return $this->redirectToRoute('app_main'); // Ou la route de ton formulaire
        }

        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('danger', 'L\'adresse email n\'est pas valide.');
            return $this->redirectToRoute('app_main');
        }

        try {
            // 3. Création de l'email
            $email = (new Email())
                ->from('noreply@ton-site.com') // Il est préférable d'utiliser l'email de ton domaine
                ->replyTo($userEmail) // Pour répondre directement au client
                ->to('admin@ton-site.com') 
                ->subject('Nouveau message de contact : ' . $nom)
                ->html("
                    <h3>Nouveau message de contact</h3>
                    <p><strong>Nom :</strong> $nom</p>
                    <p><strong>Email :</strong> $userEmail</p>
                    <p><strong>Message :</strong></p>
                    <p>$messageContent</p>
                ");

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé !');
        } catch (\Exception $e) {
            // En cas de problème technique (serveur mail mal configuré)
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi.');
        }

        return $this->redirectToRoute('app_main'); 
    }
}