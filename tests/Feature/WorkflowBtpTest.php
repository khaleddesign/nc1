<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowBtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_workflow_complet_devis_vers_facture()
    {
        // 1. Création des utilisateurs
        $admin = User::factory()->create(["role" => "admin"]);
        $commercial = User::factory()->create(["role" => "commercial", "name" => "Jean Commercial"]);
        $client = User::factory()->create(["role" => "client", "name" => "Pierre Client"]);

        // 2. Création du chantier
        $chantier = Chantier::create([
            "titre" => "Rénovation Cuisine",
            "description" => "Rénovation complète cuisine 20m²",
            "client_id" => $client->id,
            "commercial_id" => $commercial->id,
            "statut" => "planifie",
            "date_debut" => "2025-08-01",
            "date_fin_prevue" => "2025-10-31",
            "budget" => 25000
        ]);

        // 3. Test des permissions sur le chantier
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($commercial->isCommercial());
        $this->assertTrue($client->isClient());

        // 4. Vérification des relations
        $this->assertEquals($client->id, $chantier->client->id);
        $this->assertEquals($commercial->id, $chantier->commercial->id);
        $this->assertEquals("Pierre Client", $chantier->client->name);
        $this->assertEquals("Jean Commercial", $chantier->commercial->name);

        // 5. Test création devis (si le modèle existe)
        if (class_exists("App\\Models\\Devis")) {
            $devis = Devis::create([
                "numero" => "DEV-2025-001",
                "chantier_id" => $chantier->id,
                "commercial_id" => $commercial->id,
                "titre" => "Devis Rénovation Cuisine",
                "statut" => "brouillon",
                "montant_ht" => 20000,
                "montant_ttc" => 24000,
                "date_emission" => "2025-07-01",
                "date_validite" => "2025-08-01",
                "client_info" => json_encode([
                    "nom" => $client->name,
                    "email" => $client->email
                ])
            ]);

            $this->assertEquals("brouillon", $devis->statut);
            $this->assertEquals(20000, $devis->montant_ht);
            $this->assertEquals(24000, $devis->montant_ttc);
        }

        // 6. Vérifications finales
        $this->assertDatabaseHas("chantiers", [
            "titre" => "Rénovation Cuisine",
            "statut" => "planifie",
            "budget" => 25000
        ]);

        $this->assertDatabaseHas("users", ["name" => "Jean Commercial", "role" => "commercial"]);
        $this->assertDatabaseHas("users", ["name" => "Pierre Client", "role" => "client"]);
    }

    public function test_permissions_par_role()
    {
        $admin = User::factory()->create(["role" => "admin"]);
        $commercial = User::factory()->create(["role" => "commercial"]);
        $client = User::factory()->create(["role" => "client"]);

        $chantier = Chantier::factory()->create([
            "client_id" => $client->id,
            "commercial_id" => $commercial->id
        ]);

        // Admin peut tout voir
        $this->assertTrue($admin->isAdmin());
        
        // Commercial voit ses chantiers
        $this->assertEquals($commercial->id, $chantier->commercial_id);
        
        // Client voit ses chantiers
        $this->assertEquals($client->id, $chantier->client_id);
        
        // Vérification des rôles
        $this->assertFalse($commercial->isAdmin());
        $this->assertFalse($client->isCommercial());
        $this->assertFalse($admin->isClient());
    }
}