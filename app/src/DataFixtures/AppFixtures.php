<?php

namespace App\DataFixtures;

use App\Entity\Workshops;
use App\Entity\Coachs;
use App\Entity\WorkshopsType;
use App\Entity\Subscribes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- 1. LES ABONNEMENTS (Seule table avec createdAt) ---
        $abos = [
            ['titre' => 'ABONNEMENT BASIC', 'prix' => 19.90, 'desc' => 'SALLE - Accès 7j/7 de 6h à 23h'],
            ['titre' => 'ABONNEMENT PREMIUM', 'prix' => 39.90, 'desc' => 'SALLE - Accès 7j/7 de 6h à 23h;COURS COLLECTIFS - Accès 6j/7;'],
            ['titre' => 'ABONNEMENT GOLD', 'prix' => 59.90, 'desc' => 'SALLE - Accès 7j/7 de 6h à 23h;COURS COLLECTIFS - Accès 6j/7;COACHING INCLUS - séances avec un coach'],
        ];

        foreach ($abos as $data) {
            $sub = new Subscribes();
            $sub->setNameSubscribe($data['titre'])
                ->setAmount($data['prix'])
                ->setDescription($data['desc'])
                ->setCreatedAt(new \DateTimeImmutable()); // Requis par ton entité Subscribes
            $manager->persist($sub);
        }

        // --- 2. LES COACHS ---
        $coachData = [
            ['prenom' => 'Camille', 'nom' => 'Laurent', 'spec' => 'Cardio Boxing', 'bio' => 'Coach de cardio boxing, avec des cours dynamiques mêlant mouvements de boxe et travail cardiovasculaire.'],
            ['prenom' => 'Lucas', 'nom' => 'Martin', 'spec' => 'BodyPump', 'bio' => 'Coach musculation spécialisé en BodyPump, un entraînement collectif avec barre légère.'],
            ['prenom' => 'Sarah', 'nom' => 'Benali', 'spec' => 'Yoga', 'bio' => 'Coach yoga axée sur la respiration, la souplesse et la relaxation.'],
            ['prenom' => 'Inès', 'nom' => 'Moreau', 'spec' => 'Pilates', 'bio' => 'Coach Pilates centrée sur le gainage, la posture et le contrôle du mouvement.']
        ];

        $coachsEntities = [];
        foreach ($coachData as $data) {
            $coach = new Coachs();
            $coach->setFirstName($data['prenom'])
                ->setLastName($data['nom'])
                ->setSpeciality($data['spec'])
                ->setBio($data['bio']);
            $manager->persist($coach);
            $coachsEntities[] = $coach;
        }

        // --- 3. LES TYPES DE WORKSHOPS ---
        $nomsTypes = [
            'Souplesse et remise en forme',
            'Renforcement musculaire',
            'Cross-Training',
            'Séance perso'
        ];

        $typesEntities = [];
        foreach ($nomsTypes as $nom) {
            $type = new WorkshopsType();
            $type->setName($nom);
            $manager->persist($type);
            $typesEntities[$nom] = $type;
        }

        // --- 4. LES ACTIVITÉS COLLECTIVES ---
        $activitesCollectives = [
            ['nom' => 'Sport Santé', 'type' => 'Souplesse et remise en forme', 'desc' => 'Séance adaptée pour reprendre une activité physique en douceur et sécurisée.', 'image' => 'sportSante.png'],
            ['nom' => 'Yoga', 'type' => 'Souplesse et remise en forme', 'desc' => 'Harmonie du corps et de l’esprit à travers des postures et la respiration.', 'image' => 'yoga.png'],
            ['nom' => 'Stretching', 'type' => 'Souplesse et remise en forme', 'desc' => 'Exercices d’étirements pour gagner en souplesse et relâcher les tensions.', 'image' => 'stretching.png'],
            ['nom' => 'Pilates', 'type' => 'Souplesse et remise en forme', 'desc' => 'Renforcement des muscles profonds pour améliorer la posture et l’équilibre.', 'image' => 'pilates.png'],
            ['nom' => 'Body Pump', 'type' => 'Renforcement musculaire', 'desc' => 'Cours de fitness avec haltères pour sculpter l’ensemble de votre corps.', 'image' => 'bodyPump.png'],
            ['nom' => 'CAF', 'type' => 'Renforcement musculaire', 'desc' => 'Ciblage intensif : Cuisses, Abdos et Fessiers pour une silhouette tonique.', 'image' => 'CAF.png'],
            ['nom' => 'Abdos Flash', 'type' => 'Renforcement musculaire', 'desc' => '30 minutes intensives dédiées au gainage et à la sangle abdominale.', 'image' => 'abdosFlash.png'],
            ['nom' => 'BBE', 'type' => 'Renforcement musculaire', 'desc' => 'Bras, Buste et Épaules : un focus sur le haut du corps.', 'image' => 'BBE.png'],
            ['nom' => 'Cross-Training', 'type' => 'Cross-Training', 'desc' => 'Entraînement fonctionnel combinant force, cardio et agilité.', 'image' => 'crossTraining.png'],
            ['nom' => 'Cardio Flash', 'type' => 'Cross-Training', 'desc' => 'Boostez votre rythme cardiaque et brûlez un maximum de calories.', 'image' => 'cardioFlash.png'],
            ['nom' => 'Step', 'type' => 'Cross-Training', 'desc' => 'Chorégraphie dynamique sur marche pour travailler le cardio et la coordination.', 'image' => 'step.png'],
            ['nom' => 'Boxing', 'type' => 'Cross-Training', 'desc' => 'Défoulement garanti avec des techniques de boxe sans contact.', 'image' => 'boxing.png'],
        ];

        // --- 5. LOGIQUE PLANNING (24 cours) ---
        $sacDeCours = array_merge($activitesCollectives, $activitesCollectives);
        shuffle($sacDeCours);

        $joursSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $creneaux = [9, 11, 15, 18];
        $index = 0;

        foreach ($joursSemaine as $nomJour) {
            foreach ($creneaux as $heure) {
                if ($index >= count($sacDeCours)) break;

                $info = $sacDeCours[$index];
                $index++;

                $workshop = new Workshops();
                $workshop->setNameClass($info['nom'])
                    ->setDescriptionClass($info['desc'])
                    ->setImage($info['image']) 
                    ->setDayClass($nomJour)
                    ->setStartTime((new \DateTime())->setTime($heure, 0))
                    ->setEndTime((new \DateTime())->setTime($heure + 1, 0))
                    ->setStatus("Disponible")
                    ->setMaxCapacity(20)
                    ->setCoach($coachsEntities[array_rand($coachsEntities)])
                    ->setWorkshopType($typesEntities[$info['type']]);

                $manager->persist($workshop);
            }
        }

        // --- 6. SÉANCES PERSO ---
        $activitesPerso = [
            ['nom' => 'Musculation', 'type' => 'Séance perso', 'desc' => 'Coaching privé pour apprendre les bons mouvements.', 'image' => 'bodyPump.png'],
            ['nom' => 'Coaching Yoga', 'type' => 'Séance perso', 'desc' => 'Séance individuelle approfondie.', 'image' => 'yoga.png'],
            ['nom' => 'Coaching Pilates', 'type' => 'Séance perso', 'desc' => 'Travail personnalisé sur le centre du corps.', 'image' => 'pilates.png'],
            ['nom' => 'Coaching Cardio', 'type' => 'Séance perso', 'desc' => 'Programme sur mesure pour l’endurance.', 'image' => 'cardioFlash.png'],
        ];

        foreach ($activitesPerso as $info) {
            $wsP = new Workshops();
            $wsP->setNameClass($info['nom'])
                ->setDescriptionClass($info['desc'])
                ->setImage($info['image']) // <--- AJOUTE CETTE LIGNE ICI
                ->setDayClass("Sur RDV")
                ->setStartTime((new \DateTime())->setTime(0, 0))
                ->setEndTime((new \DateTime())->setTime(0, 0))
                ->setStatus("Réservé Gold")
                ->setMaxCapacity(1)
                ->setCoach($coachsEntities[array_rand($coachsEntities)])
                ->setWorkshopType($typesEntities['Séance perso']);

            $manager->persist($wsP);
        }

        $manager->flush();
    }
}
