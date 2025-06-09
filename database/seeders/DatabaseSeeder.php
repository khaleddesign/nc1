<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Chantier;
use App\Models\Etape;
use App\Models\Document;
use App\Models\Commentaire;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pour SQLite, pas besoin de désactiver les foreign keys
        // SQLite gère automatiquement les contraintes

        // Vider les tables en respectant l'ordre des dépendances
        Notification::truncate();
        Commentaire::truncate();
        Document::truncate();
        Etape::truncate();
        Chantier::truncate();
        User::truncate();

        // Créer un admin par défaut
        $admin = User::create([
            'name' => 'Administrateur Principal',
            'email' => 'admin@chantiers.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '+33 1 23 45 67 89',
            'adresse' => '1 rue de l\'Administration, 75001 Paris',
            'active' => true,
        ]);

        // Créer des commerciaux
        $commercial1 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@chantiers.com',
            'password' => Hash::make('password'),
            'role' => 'commercial',
            'telephone' => '+33 6 12 34 56 78',
            'adresse' => '10 avenue des Commerciaux, 75002 Paris',
            'active' => true,
        ]);

        $commercial2 = User::create([
            'name' => 'Marie Martin',
            'email' => 'marie.martin@chantiers.com',
            'password' => Hash::make('password'),
            'role' => 'commercial',
            'telephone' => '+33 6 87 65 43 21',
            'adresse' => '20 boulevard des Ventes, 75003 Paris',
            'active' => true,
        ]);

        $commercial3 = User::create([
            'name' => 'Paul Durand',
            'email' => 'paul.durand@chantiers.com',
            'password' => Hash::make('password'),
            'role' => 'commercial',
            'telephone' => '+33 6 99 88 77 66',
            'adresse' => '30 place du Commerce, 75004 Paris',
            'active' => true,
        ]);

        // Créer des clients
        $clients = [];
        $clientsData = [
            [
                'name' => 'Pierre Bernard',
                'email' => 'pierre.bernard@email.com',
                'telephone' => '+33 6 11 11 11 11',
                'adresse' => '5 rue des Projets, 75010 Paris',
            ],
            [
                'name' => 'Sophie Lefebvre',
                'email' => 'sophie.lefebvre@email.com',
                'telephone' => '+33 6 22 22 22 22',
                'adresse' => '15 avenue des Travaux, 75011 Paris',
            ],
            [
                'name' => 'Luc Moreau',
                'email' => 'luc.moreau@email.com',
                'telephone' => '+33 6 33 33 33 33',
                'adresse' => '25 boulevard de la Construction, 75012 Paris',
            ],
            [
                'name' => 'Anne Petit',
                'email' => 'anne.petit@email.com',
                'telephone' => '+33 6 44 44 44 44',
                'adresse' => '35 rue de la Rénovation, 75013 Paris',
            ],
            [
                'name' => 'Michel Roux',
                'email' => 'michel.roux@email.com',
                'telephone' => '+33 6 55 55 55 55',
                'adresse' => '45 place de l\'Habitat, 75014 Paris',
            ],
        ];

        foreach ($clientsData as $clientData) {
            $clients[] = User::create(array_merge($clientData, [
                'password' => Hash::make('password'),
                'role' => 'client',
                'active' => true,
            ]));
        }

        // Créer des chantiers avec différents états
        $chantiers = [];

        // Chantier 1 - En cours avec étapes avancées
        $chantier1 = Chantier::create([
            'titre' => 'Rénovation Cuisine Moderne',
            'description' => 'Rénovation complète de la cuisine avec îlot central, électroménager haut de gamme et finitions en bois noble',
            'client_id' => $clients[0]->id,
            'commercial_id' => $commercial1->id,
            'statut' => 'en_cours',
            'date_debut' => now()->subDays(45),
            'date_fin_prevue' => now()->addDays(15),
            'budget' => 35000,
            'notes' => 'Client très exigeant sur les finitions. Prévoir temps supplémentaire pour les détails.',
            'avancement_global' => 0,
        ]);
        $chantiers[] = $chantier1;

        // Étapes pour le chantier 1
        $etapes1 = [
            ['nom' => 'Démolition existant', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Gros œuvre', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Électricité', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Plomberie', 'pourcentage' => 85, 'terminee' => false],
            ['nom' => 'Carrelage sol', 'pourcentage' => 60, 'terminee' => false],
            ['nom' => 'Peinture', 'pourcentage' => 0, 'terminee' => false],
            ['nom' => 'Installation cuisine', 'pourcentage' => 0, 'terminee' => false],
            ['nom' => 'Finitions', 'pourcentage' => 0, 'terminee' => false],
        ];

        foreach ($etapes1 as $index => $etapeData) {
            Etape::create([
                'chantier_id' => $chantier1->id,
                'nom' => $etapeData['nom'],
                'description' => "Description détaillée de l'étape " . $etapeData['nom'],
                'ordre' => $index + 1,
                'pourcentage' => $etapeData['pourcentage'],
                'terminee' => $etapeData['terminee'],
                'date_debut' => $etapeData['terminee'] ? now()->subDays(45 - ($index * 5)) : ($etapeData['pourcentage'] > 0 ? now()->subDays(10) : null),
                'date_fin_prevue' => now()->subDays(40 - ($index * 5)),
                'date_fin_effective' => $etapeData['terminee'] ? now()->subDays(42 - ($index * 5)) : null,
            ]);
        }
        $chantier1->calculerAvancement();

        // Chantier 2 - Planifié
        $chantier2 = Chantier::create([
            'titre' => 'Extension Garage Double',
            'description' => 'Construction d\'une extension de garage pour 2 véhicules avec atelier',
            'client_id' => $clients[1]->id,
            'commercial_id' => $commercial1->id,
            'statut' => 'planifie',
            'date_debut' => now()->addDays(20),
            'date_fin_prevue' => now()->addDays(90),
            'budget' => 45000,
            'notes' => 'Permis de construire en cours de validation. Démarrage prévu mi-mois prochain.',
            'avancement_global' => 0,
        ]);
        $chantiers[] = $chantier2;

        // Chantier 3 - Terminé
        $chantier3 = Chantier::create([
            'titre' => 'Rénovation Salle de Bain Premium',
            'description' => 'Réfection complète avec douche italienne, baignoire îlot et finitions luxueuses',
            'client_id' => $clients[0]->id,
            'commercial_id' => $commercial2->id,
            'statut' => 'termine',
            'date_debut' => now()->subDays(120),
            'date_fin_prevue' => now()->subDays(60),
            'date_fin_effective' => now()->subDays(55),
            'budget' => 28000,
            'notes' => 'Projet terminé avec satisfaction client élevée. Excellent travail de l\'équipe.',
            'avancement_global' => 100,
        ]);
        $chantiers[] = $chantier3;

        // Étapes terminées pour le chantier 3
        $etapes3 = [
            'Démolition ancienne salle de bain',
            'Modification plomberie',
            'Électricité et éclairage',
            'Étanchéité et carrelage',
            'Installation sanitaires',
            'Peinture et finitions',
        ];

        foreach ($etapes3 as $index => $nomEtape) {
            Etape::create([
                'chantier_id' => $chantier3->id,
                'nom' => $nomEtape,
                'description' => "Étape réalisée : {$nomEtape}",
                'ordre' => $index + 1,
                'pourcentage' => 100,
                'terminee' => true,
                'date_debut' => now()->subDays(120 - ($index * 10)),
                'date_fin_prevue' => now()->subDays(115 - ($index * 10)),
                'date_fin_effective' => now()->subDays(117 - ($index * 10)),
            ]);
        }

        // Chantier 4 - En cours début
        $chantier4 = Chantier::create([
            'titre' => 'Aménagement Combles Suite Parentale',
            'description' => 'Transformation des combles en suite parentale avec dressing et salle d\'eau',
            'client_id' => $clients[2]->id,
            'commercial_id' => $commercial2->id,
            'statut' => 'en_cours',
            'date_debut' => now()->subDays(15),
            'date_fin_prevue' => now()->addDays(75),
            'budget' => 52000,
            'notes' => 'Attention hauteur sous plafond variable. Vérifier isolation phonique.',
            'avancement_global' => 0,
        ]);
        $chantiers[] = $chantier4;

        // Chantier 5 - En retard
        $chantier5 = Chantier::create([
            'titre' => 'Terrasse Bois Composite',
            'description' => 'Construction d\'une terrasse en bois composite avec pergola',
            'client_id' => $clients[3]->id,
            'commercial_id' => $commercial3->id,
            'statut' => 'en_cours',
            'date_debut' => now()->subDays(80),
            'date_fin_prevue' => now()->subDays(20), // En retard
            'budget' => 18000,
            'notes' => 'Retard dû aux intempéries exceptionnelles. Client informé.',
            'avancement_global' => 0,
        ]);
        $chantiers[] = $chantier5;

        // Chantier 6 - Autre client
        $chantier6 = Chantier::create([
            'titre' => 'Isolation Thermique Complète',
            'description' => 'Isolation des murs, toiture et changement des menuiseries',
            'client_id' => $clients[4]->id,
            'commercial_id' => $commercial3->id,
            'statut' => 'planifie',
            'date_debut' => now()->addDays(30),
            'date_fin_prevue' => now()->addDays(60),
            'budget' => 25000,
            'notes' => 'Projet aidé par les subventions énergie. Dossier en cours.',
            'avancement_global' => 0,
        ]);
        $chantiers[] = $chantier6;

        // Ajouter des commentaires
        $commentaires = [
            [
                'chantier_id' => $chantier1->id,
                'user_id' => $clients[0]->id,
                'contenu' => 'Très satisfait de l\'avancement des travaux. L\'équipe est professionnelle et respecte les délais.',
            ],
            [
                'chantier_id' => $chantier1->id,
                'user_id' => $commercial1->id,
                'contenu' => 'Merci pour votre retour positif ! Nous mettons tout en œuvre pour respecter vos attentes.',
            ],
            [
                'chantier_id' => $chantier5->id,
                'user_id' => $clients[3]->id,
                'contenu' => 'J\'aimerais avoir une nouvelle estimation de la date de fin compte tenu du retard.',
            ],
            [
                'chantier_id' => $chantier5->id,
                'user_id' => $commercial3->id,
                'contenu' => 'Nous prévoyons une finition pour la semaine prochaine, météo permettant. Je vous tiens informé.',
            ],
        ];

        foreach ($commentaires as $commentaireData) {
            Commentaire::create($commentaireData);
        }

        // Créer des notifications (avec chantier_id nullable pour les notifications système)
        $notifications = [
            [
                'user_id' => $clients[0]->id,
                'chantier_id' => $chantier1->id,
                'type' => 'etape_terminee',
                'titre' => 'Étape terminée',
                'message' => 'L\'étape "Électricité" a été terminée sur votre chantier Rénovation Cuisine.',
                'lu' => false,
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $clients[3]->id,
                'chantier_id' => $chantier5->id,
                'type' => 'chantier_retard',
                'titre' => 'Chantier en retard',
                'message' => 'Votre chantier "Terrasse Bois Composite" a dépassé sa date de fin prévue.',
                'lu' => false,
                'created_at' => now()->subHours(24),
            ],
            [
                'user_id' => $commercial1->id,
                'chantier_id' => $chantier1->id,
                'type' => 'nouveau_commentaire_client',
                'titre' => 'Nouveau commentaire client',
                'message' => 'Pierre Bernard a laissé un commentaire sur le chantier "Rénovation Cuisine Moderne".',
                'lu' => false,
                'created_at' => now()->subHours(1),
            ],
        ];

        foreach ($notifications as $notificationData) {
            Notification::create($notificationData);
        }

        // Créer quelques documents (métadonnées seulement)
        $documents = [
            [
                'chantier_id' => $chantier1->id,
                'user_id' => $commercial1->id,
                'nom_original' => 'devis_cuisine_detaille.pdf',
                'nom_fichier' => Str::uuid() . '.pdf',
                'chemin' => 'documents/' . $chantier1->id . '/' . Str::uuid() . '.pdf',
                'type_mime' => 'application/pdf',
                'taille' => 2456789,
                'description' => 'Devis détaillé pour la rénovation de la cuisine',
                'type' => 'document',
            ],
            [
                'chantier_id' => $chantier1->id,
                'user_id' => $commercial1->id,
                'nom_original' => 'plan_cuisine_3d.jpg',
                'nom_fichier' => Str::uuid() . '.jpg',
                'chemin' => 'documents/' . $chantier1->id . '/' . Str::uuid() . '.jpg',
                'type_mime' => 'image/jpeg',
                'taille' => 1834567,
                'description' => 'Vue 3D du projet de cuisine avec îlot central',
                'type' => 'plan',
            ],
            [
                'chantier_id' => $chantier3->id,
                'user_id' => $commercial2->id,
                'nom_original' => 'facture_finale_sdb.pdf',
                'nom_fichier' => Str::uuid() . '.pdf',
                'chemin' => 'documents/' . $chantier3->id . '/' . Str::uuid() . '.pdf',
                'type_mime' => 'application/pdf',
                'taille' => 987654,
                'description' => 'Facture finale des travaux',
                'type' => 'facture',
            ],
        ];

        foreach ($documents as $documentData) {
            Document::create($documentData);
        }

        // Calculer l'avancement de tous les chantiers
        $chantiers = Chantier::all();
        foreach ($chantiers as $chantier) {
            $chantier->calculerAvancement();
        }

        $this->command->info('Base de données peuplée avec succès !');
        $this->command->info('');
        $this->command->info('Comptes de test :');
        $this->command->info('Admin : admin@chantiers.com / password');
        $this->command->info('Commercial : jean.dupont@chantiers.com / password');
        $this->command->info('Client : pierre.bernard@email.com / password');
    }
}