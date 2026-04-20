<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Users;
use App\Entity\Take;
use App\Form\RegistrationType;
use App\Form\UserProfileType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // On cherche dans la table 'Take'
        // Attention : ton entité Take utilise 'user' (sans s) pour lier à Users
        $activeSubscription = $em->getRepository(Take::class)->findOneBy([
            'user' => $user,     // Vérifie bien dans Take.php si c'est 'user' ou 'users'
            'is_active' => true   // Et si c'est 'isActive' ou 'is_active'
        ]);

        $form = $this->createForm(UserProfileType::class, $user, [
            'action' => $this->generateUrl('app_profile_update_photo'),
            'method' => 'POST',
        ]);

        return $this->render('main/dashboard.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'activeSubscription' => $activeSubscription,
        ]);
    }

    #[Route('/a-propos', name: 'app_a-propos')]
    public function aPropos(): Response
    {
        return $this->render('main/a-propos.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige direct au dashboard
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony gère ça tout seul !
        throw new \LogicException('This method can be blank');
    }

    #[Route('/register', name: 'app_register')]

    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        // 1. On crée un nouvel utilisateur vide
        $user = new Users();

        // 2. On crée le formulaire et on le lie à l'utilisateur
        $form = $this->createForm(RegistrationType::class, $user);

        // 3. On dit au formulaire de regarder si des données ont été envoyées (POST)
        $form->handleRequest($request);

        // 4. Si le formulaire est soumis et que les contrôles (email, password...) sont OK
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère le mot de passe en clair pour le hacher (le crypter)
            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // On définit les champs automatiques
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt(new \DateTimeImmutable());

            // On initialise le dernier login à maintenant
            $user->setLastLogin(new \DateTime());

            // 5. On enregistre en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // On redirige vers l'accueil avec un petit message de succès
            $this->addFlash('success', 'Inscription réussie ! Bienvenue chez Gym Accord !');
            return $this->redirectToRoute('app_login');
        }

        // 6. On envoie la VUE du formulaire au fichier Twig
        return $this->render('main/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
