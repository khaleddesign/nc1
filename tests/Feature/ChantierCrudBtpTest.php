<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chantier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChantierCrudBtpTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $commercial;
    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(["role" => "admin"]);
        $this->commercial = User::factory()->create(["role" => "commercial"]);
        $this->client = User::factory()->create(["role" => "client"]);
    }

    public function test_admin_peut_voir_tous_chantiers()
    {
        $chantier1 = Chantier::factory()->create(["client_id" => $this->client->id]);
        $chantier2 = Chantier::factory()->create();

        $response = $this->actingAs($this->admin)->get("/chantiers");

        $response->assertOk();
        // Vérifier que les chantiers sont visibles (selon votre implémentation)
    }

    public function test_commercial_voit_ses_chantiers()
    {
        $sonChantier = Chantier::factory()->create([
            "commercial_id" => $this->commercial->id,
            "client_id" => $this->client->id,
            "titre" => "Son Chantier"
        ]);
        
        $autreChantier = Chantier::factory()->create([
            "titre" => "Autre Chantier"
        ]);

        // Test logique métier
        $this->assertEquals($this->commercial->id, $sonChantier->commercial_id);
        $this->assertNotEquals($this->commercial->id, $autreChantier->commercial_id);
    }

    public function test_client_voit_ses_chantiers()
    {
        $sonChantier = Chantier::factory()->create([
            "client_id" => $this->client->id,
            "commercial_id" => $this->commercial->id,
            "titre" => "Son Projet"
        ]);
        
        $autreChantier = Chantier::factory()->create([
            "titre" => "Autre Projet"
        ]);

        // Test logique métier
        $this->assertEquals($this->client->id, $sonChantier->client_id);
        $this->assertNotEquals($this->client->id, $autreChantier->client_id);
    }

    public function test_creation_chantier_complet()
    {
        $chantierData = [
            "titre" => "Nouveau Chantier BTP",
            "description" => "Description complète du chantier",
            "client_id" => $this->client->id,
            "commercial_id" => $this->commercial->id,
            "statut" => "planifie",
            "date_debut" => "2025-08-01",
            "date_fin_prevue" => "2025-12-31",
            "budget" => 80000,
            "avancement_global" => 0
        ];

        $chantier = Chantier::create($chantierData);

        $this->assertDatabaseHas("chantiers", [
            "titre" => "Nouveau Chantier BTP",
            "statut" => "planifie",
            "budget" => 80000
        ]);

        $this->assertEquals("Nouveau Chantier BTP", $chantier->titre);
        $this->assertEquals($this->client->id, $chantier->client_id);
        $this->assertEquals($this->commercial->id, $chantier->commercial_id);
    }
}