<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Feature\Traits\WithApiTesting;
use Tests\Feature\Traits\WithTestData;

class ApiTest extends TestCase
{
    use RefreshDatabase, WithApiTesting, WithTestData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTestData();
    }

    public function test_api_authentication_required()
    {
        $response = $this->getJson('/api/chantiers');
        $response->assertStatus(401);
    }

    public function test_api_chantiers_list()
    {
        Sanctum::actingAs($this->commercial);

        $chantier = Chantier::factory()->create([
            'commercial_id' => $this->commercial->id
        ]);

        $response = $this->getJson('/api/chantiers');
        
        $this->assertJsonApiCollection($response);
        $response->assertJsonFragment([
            'titre' => $chantier->titre
        ]);
    }

    public function test_api_chantier_show()
    {
        Sanctum::actingAs($this->commercial);

        $chantier = Chantier::factory()->create([
            'commercial_id' => $this->commercial->id
        ]);

        $response = $this->getJson("/api/chantiers/{$chantier->id}");
        
        $this->assertJsonApiResource($response, [
            'titre' => $chantier->titre,
            'statut' => $chantier->statut
        ]);
    }

    public function test_api_devis_creation()
    {
        Sanctum::actingAs($this->commercial);

        $devisData = [
            'titre' => 'Devis API',
            'chantier_id' => $this->chantier->id,
            'date_validite' => '2025-12-31',
            'lignes' => [
                [
                    'designation' => 'Service API',
                    'quantite' => 5,
                    'prix_unitaire' => 100,
                    'tva' => 20
                ]
            ]
        ];

        $response = $this->postJson('/api/devis', $devisData);
        
        $this->assertJsonApiResource($response, ['titre' => 'Devis API'], 201);
        $this->assertDatabaseHas('devis', ['titre' => 'Devis API']);
    }

    public function test_api_rate_limiting()
    {
        Sanctum::actingAs($this->commercial);

        // Simuler beaucoup de requêtes
        for ($i = 0; $i < 61; $i++) {
            $response = $this->getJson('/api/chantiers');
            
            if ($i < 60) {
                $response->assertStatus(200);
            } else {
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }

    public function test_api_validation_errors()
    {
        Sanctum::actingAs($this->commercial);

        $response = $this->postJson('/api/devis', []);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['titre', 'chantier_id']);
    }

    public function test_api_permissions_respect()
    {
        Sanctum::actingAs($this->client);

        // Client ne peut pas créer de devis via API
        $response = $this->postJson('/api/devis', [
            'titre' => 'Tentative client',
            'chantier_id' => $this->chantier->id
        ]);

        $response->assertStatus(403);
    }
}