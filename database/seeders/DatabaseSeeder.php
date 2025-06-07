<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        // Créer un admin par défaut
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@chantiers.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '0123456789',
            'adresse' => '1 rue de l\'Admin, 75001 Paris',
            'active' => true,
        ]);

        // Créer des commerciaux
        $commercial1 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@chantiers.com',
            'password' => Hash::make('password'),
            'role' => 'commercial',
            'telephone' => '0612345678',
            'adresse' => '10 avenue des Commerciaux, 75002 Paris',
            'active' => true,
        ]);

        $commercial2 = User::create([
            'name' => 'Marie Martin',
            'email' => 'marie.martin@chantiers.com',
            'password' => Hash::make('password'),
            'role' => 'commercial',
            'telephone' => '0687654321',
            'adresse' => '20 boulevard des Ventes, 75003 Paris',
            'active' => true,
        ]);

        // Créer des clients
        $client1 = User::create([
            'name' => 'Pierre Durand',
            'email' => 'pierre.durand@email.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '0611111111',
            'adresse' => '5 rue des Clients, 75010 Paris',
            'active' => true,
        ]);

        $client2 = User::create([
            'name' => 'Sophie Bernard',
            'email' => 'sophie.bernard@email.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '0622222222',
            'adresse' => '15 avenue des Projets, 75011 Paris',
            'active' => true,
        ]);

        $client3 = User::create([
            'name' => 'Luc Moreau',
            'email' => 'luc.moreau@email.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '0633333333',
            'adresse' => '25 boulevard des Travaux, 75012 Paris',
            'active' => true,
        ]);

        // Créer des chantiers
        $chantier1 = Chantier::create([
            'titre' => 'Rénovation Cuisine Moderne',
            'description' => 'Rénovation complète de la cuisine avec îlot central et électroménager haut de gamme',
            'client_id' => $client1->id,
            'commercial_id' => $commercial1->id,
            'statut' => 'en_cours',
            'date_debut' => now()->subDays(30),
            'date_fin_prevue' => now()->addDays(30),
            'budget' => 25000,
            'notes' => 'Client souhaite des finitions en bois noble',
            'avancement_global' => 0,
        ]);

        // Créer des étapes pour le chantier 1
        $etapes1 = [
            ['nom' => 'Démolition', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Électricité', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Plomberie', 'pourcentage' => 80, 'terminee' => false],
            ['nom' => 'Carrelage', 'pourcentage' => 20, 'terminee' => false],
            ['nom' => 'Installation cuisine', 'pourcentage' => 0, 'terminee' => false],
            ['nom' => 'Finitions', 'pourcentage' => 0, 'terminee' => false],
        ];

        foreach ($etapes1 as $index => $etapeData) {
            Etape::create([
                'chantier_id' => $chantier1->id,
                'nom' => $etapeData['nom'],
                'description' => "Description de l'étape " . $etapeData['nom'],
                'ordre' => $index + 1,
                'pourcentage' => $etapeData['pourcentage'],
                'terminee' => $etapeData['terminee'],
                'date_debut' => now()->subDays(30 - ($index * 5)),
                'date_fin_prevue' => now()->subDays(25 - ($index * 5)),
            ]);
        }

        $chantier1->calculerAvancement();

        $chantier2 = Chantier::create([
            'titre' => 'Extension Garage',
            'description' => 'Construction d\'une extension de garage pour 2 véhicules',
            'client_id' => $client2->id,
            'commercial_id' => $commercial1->id,
            'statut' => 'planifie',
            'date_debut' => now()->addDays(15),
            'date_fin_prevue' => now()->addDays(75),
            'budget' => 35000,
            'notes' => 'Permis de construire en cours',
            'avancement_global' => 0,
        ]);

        $chantier3 = Chantier::create([
            'titre' => 'Rénovation Salle de Bain',
            'description' => 'Réfection complète avec douche italienne',
            'client_id' => $client1->id,
            'commercial_id' => $commercial2->id,
            'statut' => 'termine',
            'date_debut' => now()->subDays(90),
            'date_fin_prevue' => now()->subDays(30),
            'date_fin_effective' => now()->subDays(28),
            'budget' => 15000,
            'notes' => 'Projet terminé avec satisfaction client',
            'avancement_global' => 100,
        ]);

        // Créer des étapes pour le chantier 3 (terminé)
        $etapes3 = [
            ['nom' => 'Démolition', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Plomberie', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Électricité', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Carrelage', 'pourcentage' => 100, 'terminee' => true],
            ['nom' => 'Installation sanitaires', 'pourcentage' => 100, 'terminee' => true],
        ];

        foreach ($etapes3 as $index => $etapeData) {
            Etape::create([
                'chantier_id' => $chantier3->id,
                'nom' => $etapeData['nom'],
                'description' => "Description de l'étape " . $etapeData['nom'],
                'ordre' => $index + 1,
                'pourcentage' => $etapeData['pourcentage'],
                'terminee' => $etapeData['terminee'],
                'date_debut' => now()->subDays(90 - ($index * 10)),
                'date_fin_prevue' => now()->subDays(80 - ($index * 10)),
                'date_fin_effective' => now()->subDays(78 - ($index * 10)),
            ]);
        }

        $chantier4 = Chantier::create([
            'titre' => 'Aménagement Combles',
            'description' => 'Transformation des combles en suite parentale',
            'client_id' => $client3->id,
            'commercial_id' => $commercial2->id,
            'statut' => 'en_cours',
            'date_debut' => now()->subDays(10),
            'date_fin_prevue' => now()->addDays(50),
            'budget' => 45000,
            'notes' => 'Attention à la hauteur sous plafond',
            'avancement_global' => 0,
        ]);

        // Créer un chantier en retard
        $chantier5 = Chantier::create([
            'titre' => 'Terrasse Extérieure',
            'description' => 'Construction d\'une terrasse en bois composite',
            'client_id' => $client2->id,
            'commercial_id' => $commercial1->id,
            'statut' => 'en_cours',
            'date_debut' => now()->subDays(60),
            'date_fin_prevue' => now()->subDays(10), // Date dépassée
            'budget' => 12000,
            'notes' => 'Retard dû aux intempéries',
            'avancement_global' => 0,
        ]);

        // Ajouter des commentaires
        Commentaire::create([
            'chantier_id' => $chantier1->id,
            'user_id' => $client1->id,
            'contenu' => 'Très satisfait de l\'avancement des travaux. L\'équipe est professionnelle.',
        ]);

        Commentaire::create([
            'chantier_id' => $chantier1->id,
            'user_id' => $commercial1->id,
            'contenu' => 'Merci pour votre retour. Nous restons à votre disposition pour toute question.',
        ]);

        Commentaire::create([
            'chantier_id' => $chantier5->id,
            'user_id' => $client2->id,
            'contenu' => 'Y a-t-il une nouvelle date de fin prévue pour les travaux ?',
        ]);

        // Créer des notifications
        Notification::create([
            'user_id' => $client1->id,
            'chantier_id' => $chantier1->id,
            'type' => 'etape_terminee',
            'titre' => 'Étape terminée',
            'message' => 'L\'étape "Électricité" a été terminée sur votre chantier.',
            'lu' => false,
        ]);

        Notification::create([
            'user_id' => $client2->id,
            'chantier_id' => $chantier5->id,
            'type' => 'chantier_retard',
            'titre' => 'Chantier en retard',
            'message' => 'Votre chantier "Terrasse Extérieure" a dépassé sa date de fin prévue.',
            'lu' => false,
        ]);

        Notification::create([
            'user_id' => $commercial1->id,
            'chantier_id' => $chantier1->id,
            'type' => 'nouveau_commentaire_client',
            'titre' => 'Nouveau commentaire client',
            'message' => 'Pierre Durand a laissé un commentaire sur le chantier "Rénovation Cuisine Moderne".',
            'lu' => false,
        ]);

        // Créer quelques documents (métadonnées uniquement)
        Document::create([
            'chantier_id' => $chantier1->id,
            'user_id' => $commercial1->id,
            'nom_original' => 'devis_cuisine.pdf',
            'nom_fichier' => Str::uuid() . '.pdf',
            'chemin' => 'documents/' . $chantier1->id . '/' . Str::uuid() . '.pdf',
            'type_mime' => 'application/pdf',
            'taille' => 2456789,
            'description' => 'Devis détaillé pour la rénovation de la cuisine',
            'type' => 'document',
        ]);

        Document::create([
            'chantier_id' => $chantier1->id,
            'user_id' => $commercial1->id,
            'nom_original' => 'plan_cuisine_3d.jpg',
            'nom_fichier' => Str::uuid() . '.jpg',
            'chemin' => 'documents/' . $chantier1->id . '/' . Str::uuid() . '.jpg',
            'type_mime' => 'image/jpeg',
            'taille' => 1234567,
            'description' => 'Vue 3D du projet de cuisine',
            'type' => 'plan',
        ]);

        Document::create([
            'chantier_id' => $chantier3->id,
            'user_id' => $commercial2->id,
            'nom_original' => 'facture_finale_sdb.pdf',
            'nom_fichier' => Str::uuid() . '.pdf',
            'chemin' => 'documents/' . $chantier3->id . '/' . Str::uuid() . '.pdf',
            'type_mime' => 'application/pdf',
            'taille' => 987654,
            'description' => 'Facture finale des travaux',
            'type' => 'facture',
        ]);

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
        $this->command->info('Client : pierre.durand@email.com / password');
    }
}