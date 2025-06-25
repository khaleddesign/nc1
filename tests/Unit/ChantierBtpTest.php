<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Chantier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChantierBtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_chantier_creation()
    {
        $client = User::factory()->create(["role" => "client"]);
        $commercial = User::factory()->create(["role" => "commercial"]);
        
        $chantier = Chantier::create([
            "titre" => "Rénovation Maison",
            "description" => "Rénovation complète",
            "client_id" => $client->id,
            "commercial_id" => $commercial->id,
            "statut" => "en_cours",
            "date_debut" => "2025-07-01",
            "date_fin_prevue" => "2025-12-31",
            "budget" => 75000
        ]);

        $this->assertEquals("Rénovation Maison", $chantier->titre);
        $this->assertEquals("en_cours", $chantier->statut);
        $this->assertEquals(75000, $chantier->budget);
    }

    public function test_chantier_relations()
    {
        $client = User::factory()->create(["role" => "client"]);
        $commercial = User::factory()->create(["role" => "commercial"]);
        
        $chantier = Chantier::factory()->create([
            "client_id" => $client->id,
            "commercial_id" => $commercial->id
        ]);

        $this->assertEquals($client->id, $chantier->client->id);
        $this->assertEquals($commercial->id, $chantier->commercial->id);
        $this->assertEquals("client", $chantier->client->role);
        $this->assertEquals("commercial", $chantier->commercial->role);
    }
}