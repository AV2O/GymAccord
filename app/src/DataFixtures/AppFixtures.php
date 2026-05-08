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
            [
                'nom' => 'Sport Santé',
                'type' => 'Souplesse et remise en forme',
                'desc' => 'Séance douce et progressive adaptée à tous les niveaux, idéale pour reprendre une activité physique après une longue pause. Les exercices sont conçus pour renforcer le corps en toute sécurité, sans impact excessif sur les articulations. Un encadrement bienveillant pour retrouver confiance et bien-être durablement.',
                'image' => 'sportSante.webp'
            ],
            [
                'nom' => 'Yoga',
                'type' => 'Souplesse et remise en forme',
                'desc' => 'Une pratique millénaire alliant postures, respiration et méditation pour harmoniser le corps et l\'esprit. Chaque séance favorise la souplesse, la concentration et la gestion du stress au quotidien. Accessible à tous les niveaux, du débutant au pratiquant confirmé.',
                'image' => 'yoga.webp'
            ],
            [
                'nom' => 'Stretching',
                'type' => 'Souplesse et remise en forme',
                'desc' => 'Des exercices d\'étirements progressifs pour relâcher les tensions musculaires accumulées au fil de la journée. Cette séance améliore l\'amplitude articulaire et prévient les blessures liées à la pratique sportive. Un moment de récupération active, idéal en complément de toute autre activité.',
                'image' => 'stretching.webp'
            ],
            [
                'nom' => 'Pilates',
                'type' => 'Souplesse et remise en forme',
                'desc' => 'Méthode de renforcement des muscles profonds pour améliorer la posture, l\'équilibre et la stabilité du corps. Les mouvements précis et contrôlés travaillent la sangle abdominale, le dos et les membres inférieurs. Une discipline idéale pour corriger les déséquilibres musculaires et retrouver une silhouette harmonieuse.',
                'image' => 'pilates.webp'
            ],
            [
                'nom' => 'Body Pump',
                'type' => 'Renforcement musculaire',
                'desc' => 'Cours de fitness collectif avec haltères pour sculpter et tonifier l\'ensemble du corps en musique. Les séries de répétitions ciblées permettent de travailler chaque groupe musculaire de manière efficace et progressive. Une heure de travail intense qui combine endurance musculaire et dépense calorique.',
                'image' => 'bodyPump.webp'
            ],
            [
                'nom' => 'CAF',
                'type' => 'Renforcement musculaire',
                'desc' => 'Un ciblage intensif des trois zones clés : Cuisses, Abdos et Fessiers pour une silhouette ferme et tonique. Les exercices combinent gainage, squats et travail au sol pour des résultats visibles rapidement. Idéal pour affiner la silhouette et renforcer les zones les plus sollicitées au quotidien.',
                'image' => 'CAF.webp'
            ],
            [
                'nom' => 'Abdos Flash',
                'type' => 'Renforcement musculaire',
                'desc' => 'Une séance express de 30 minutes entièrement dédiée au gainage et à la sangle abdominale. Des exercices variés et progressifs pour renforcer les abdominaux profonds et superficiels efficacement. Un format court et intense, parfait pour s\'intégrer facilement dans un emploi du temps chargé.',
                'image' => 'abdosFlash.webp'
            ],
            [
                'nom' => 'BBE',
                'type' => 'Renforcement musculaire',
                'desc' => 'Un cours focalisé sur le haut du corps : Bras, Buste et Épaules pour un galbe musculaire harmonieux. Les exercices avec ou sans matériel ciblent les triceps, biceps, pectoraux et deltoïdes en profondeur. Une séance incontournable pour ceux qui souhaitent développer leur force et leur tonicité musculaire.',
                'image' => 'BBE.webp'
            ],
            [
                'nom' => 'Cross-Training',
                'type' => 'Cross-Training',
                'desc' => 'Entraînement fonctionnel complet combinant force explosive, cardio-training et exercices d\'agilité. Chaque séance est différente et repousse vos limites grâce à des enchaînements variés et stimulants. Un format exigeant qui développe la condition physique générale et la résistance mentale.',
                'image' => 'crossTraining.webp'
            ],
            [
                'nom' => 'Cardio Flash',
                'type' => 'Cross-Training',
                'desc' => 'Une séance haute intensité pour booster le rythme cardiaque et maximiser la dépense calorique. Les intervalles de travail court et intenses alternent avec de brèves récupérations pour un effet brûle-graisses optimal. Idéal pour améliorer l\'endurance cardiovasculaire et la capacité respiratoire.',
                'image' => 'cardioFlash.webp'
            ],
            [
                'nom' => 'Step',
                'type' => 'Cross-Training',
                'desc' => 'Une chorégraphie dynamique et rythmée sur marche pour travailler simultanément le cardio et la coordination. Les enchaînements progressifs s\'adaptent à tous les niveaux, du débutant au pratiquant avancé. Une séance conviviale et ludique qui développe le sens du rythme et la maîtrise corporelle.',
                'image' => 'step.webp'
            ],
            [
                'nom' => 'Boxing',
                'type' => 'Cross-Training',
                'desc' => 'Défoulement garanti avec des techniques de boxe adaptées, sans contact et accessibles à tous. Cette séance combine travail cardio intense, coordination des mouvements et renforcement musculaire global. Un excellent exutoire pour évacuer le stress tout en développant l\'agilité et la puissance physique.',
                'image' => 'boxing.webp'
            ],
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
            ['nom' => 'Musculation', 'type' => 'Séance perso', 'desc' => 'Coaching privé pour apprendre les bons mouvements.', 'image' => 'bodyPump.jpg'],
            ['nom' => 'Coaching Yoga', 'type' => 'Séance perso', 'desc' => 'Séance individuelle approfondie.', 'image' => 'yoga.jpg'],
            ['nom' => 'Coaching Pilates', 'type' => 'Séance perso', 'desc' => 'Travail personnalisé sur le centre du corps.', 'image' => 'pilates.jpg'],
            ['nom' => 'Coaching Cardio', 'type' => 'Séance perso', 'desc' => 'Programme sur mesure pour l’endurance.', 'image' => 'cardioFlash.jpg'],
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
