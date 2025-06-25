<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Ligne;
use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevisWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $commercial;
    private User $client;
    private Chantier $chantier;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->commercial = User::factory()->create(['role' => 'commercial']);
        $this->client = User::factory()->create(['role' => 'client']);
        
        $this->chantier = Chantier::factory()->create([
            'client_id' => $this->client->id,
            'commercial_id' => $this->commercial->id
        ]);
    }

    public function test_commercial_peut_creer_devis()
    {
        $devisData = [
            'titre' => 'Devis Test',
            'chantier_id' => $this->chantier->id,
            'date_validite' => '2025-12-31',
            'lignes' => [
                [
                    'designation' => 'Maçonnerie',
                    'quantite' => 10,
                    'prix_unitaire' => 50,
                    'tva' => 20
                ],
                [
                    'designation' => 'Plomberie', 
                    'quantite' => 5,
                    'prix_unitaire' => 100,
                    'tva' => 20
                ]
            ]
        ];

        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/devis", $devisData);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('devis', [
            'titre' => 'Devis Test',
            'chantier_id' => $this->chantier->id,
            'statut' => 'brouillon'
        ]);

        $devis = Devis::where('titre', 'Devis Test')->first();
        $this->assertCount(2, $devis->lignes);
        $this->assertEquals(1000, $devis->montant_ht); // (10*50) + (5*100)
    }

    public function test_client_ne_peut_pas_creer_devis()
    {
        $devisData = [
            'titre' => 'Devis Client',
            'chantier_id' => $this->chantier->id
        ];

        $response = $this->actingAs($this->client)
            ->post("/chantiers/{$this->chantier->id}/devis", $devisData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('devis', ['titre' => 'Devis Client']);
    }

    public function test_workflow_complet_devis_vers_facture()
    {
        // 1. Création du devis en brouillon
        $devis = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id,
            'statut' => 'brouillon',
            'montant_ht' => 1000,
            'montant_ttc' => 1200
        ]);

        Ligne::factory()->create([
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id,
            'designation' => 'Test ligne',
            'quantite' => 10,
            'prix_unitaire' => 100
        ]);

        // 2. Commercial envoie le devis
        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/devis/{$devis->id}/envoyer");

        $response->assertRedirect();
        $devis->refresh();
        $this->assertEquals('envoye', $devis->statut);

        // 3. Client accepte le devis
        $response = $this->actingAs($this->client)
            ->post("/chantiers/{$this->chantier->id}/devis/{$devis->id}/accepter");

        $response->assertRedirect();
        $devis->refresh();
        $this->assertEquals('accepte', $devis->statut);

        // 4. Conversion automatique en facture
        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/devis/{$devis->id}/convertir-facture");

        $response->assertRedirect();
        
        $this->assertDatabaseHas('factures', [
            'chantier_id' => $this->chantier->id,
            'devis_id' => $devis->id,
            'montant_ht' => 1000,
            'montant_ttc' => 1200,
            'statut' => 'envoyee'
        ]);

        $facture = Facture::where('devis_id', $devis->id)->first();
        $this->assertCount(1, $facture->lignes);
    }

    public function test_client_peut_refuser_devis()
    {
        $devis = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'statut' => 'envoye'
        ]);

        $response = $this->actingAs($this->client)
            ->post("/chantiers/{$this->chantier->id}/devis/{$devis->id}/refuser", [
                'motif' => 'Prix trop élevé'
            ]);

        $response->assertRedirect();
        $devis->refresh();
        $this->assertEquals('refuse', $devis->statut);
    }

    public function test_devis_expire_ne_peut_pas_etre_accepte()
    {
        $devis = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'statut' => 'envoye',
            'date_validite' => '2024-01-01' // Date expirée
        ]);

        $response = $this->actingAs($this->client)
            ->post("/chantiers/{$this->chantier->id}/devis/{$devis->id}/accepter");

        $response->assertStatus(422);
        $devis->refresh();
        $this->assertEquals('envoye', $devis->statut);
    }

    public function test_validation_modification_devis_selon_statut()
    {
        // Devis en brouillon peut être modifié
        $devisBrouillon = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id,
            'statut' => 'brouillon'
        ]);

        $response = $this->actingAs($this->commercial)
            ->put("/chantiers/{$this->chantier->id}/devis/{$devisBrouillon->id}", [
                'titre' => 'Titre Modifié'
            ]);

        $response->assertRedirect();

        // Devis accepté ne peut pas être modifié
        $devisAccepte = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id,
            'statut' => 'accepte'
        ]);

        $response = $this->actingAs($this->commercial)
            ->put("/chantiers/{$this->chantier->id}/devis/{$devisAccepte->id}", [
                'titre' => 'Tentative Modification'
            ]);

        $response->assertStatus(422);
    }

    public function test_calcul_automatique_montants_devis()
    {
        $devisData = [
            'titre' => 'Devis Calcul',
            'chantier_id' => $this->chantier->id,
            'date_validite' => '2025-12-31',
            'lignes' => [
                [
                    'designation' => 'Ligne 1',
                    'quantite' => 10,
                    'prix_unitaire' => 100, // 1000 HT
                    'tva' => 20
                ],
                [
                    'designation' => 'Ligne 2',
                    'quantite' => 5,
                    'prix_unitaire' => 50, // 250 HT
                    'tva' => 10
                ]
            ]
        ];

        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/devis", $devisData);

        $devis = Devis::where('titre', 'Devis Calcul')->first();
        
        $this->assertEquals(1250, $devis->montant_ht); // 1000 + 250
        $this->assertEquals(1525, $devis->montant_ttc); // 1000*1.2 + 250*1.1
    }
}