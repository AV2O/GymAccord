<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile/update-photo', name: 'app_profile_update_photo', methods: ['POST'])]
    public function updatePhoto(Request $request, EntityManagerInterface $em): Response
    {
        /** @var Users $user */
        $user = $this->getUser();
        if (!$user instanceof Users) { return $this->redirectToRoute('app_login'); }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('profilePicture')->getData();

            if ($pictureFile) {
                try {
                    // 1. Nettoyage de l'ancienne photo
                    if ($user->getProfilePicture()) {
                        $oldPath = $this->getParameter('profiles_directory') . '/' . $user->getProfilePicture();
                        if (file_exists($oldPath)) { unlink($oldPath); }
                    }

                    // 2. Préparation du nouveau fichier
                    $newFilename = bin2hex(random_bytes(10)) . '.webp';
                    $destination = $this->getParameter('profiles_directory') . '/' . $newFilename;
                    $sourcePath = $pictureFile->getRealPath();

                    // 3. REDIMENSIONNEMENT NATIF (Logique de ton schéma)
                    $info = getimagesize($sourcePath);
                    $source = match($info['mime']) {
                        'image/jpeg' => imagecreatefromjpeg($sourcePath),
                        'image/png'  => imagecreatefrompng($sourcePath),
                        'image/gif'  => imagecreatefromgif($sourcePath),
                        default      => throw new \Exception("Format non supporté")
                    };

                    $width = imagesx($source);
                    $height = imagesy($source);
                    $size = min($width, $height); // On cherche le plus petit côté pour le carré

                    $target = imagecreatetruecolor(300, 300);
                    
                    // On calcule les coordonnées pour centrer (ton schéma de cours !)
                    $src_x = ($width - $size) / 2;
                    $src_y = ($height - $size) / 2;

                    imagecopyresampled($target, $source, 0, 0, $src_x, $src_y, 300, 300, $size, $size);

                    // 4. Sauvegarde et nettoyage mémoire
                    imagewebp($target, $destination, 80);
                    
                    // 5. BDD
                    $user->setProfilePicture($newFilename);
                    $em->flush();

                    $this->addFlash('success', 'Photo mise à jour !');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur : ' . $e->getMessage());
                }
            }
        }
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/profile/delete-photo', name: 'app_profile_delete_photo')]
    public function deletePhoto(EntityManagerInterface $em): Response
    {
        /** @var Users $user */
        $user = $this->getUser();

        if ($user instanceof Users && $user->getProfilePicture()) {
            $filePath = $this->getParameter('profiles_directory') . '/' . $user->getProfilePicture();
            if (file_exists($filePath)) { unlink($filePath); }

            $user->setProfilePicture(null);
            $em->flush();
            $this->addFlash('success', 'Photo supprimée.');
        }

        return $this->redirectToRoute('app_dashboard');
    }
}