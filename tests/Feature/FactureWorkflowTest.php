<?php

// tests/Feature/FactureWorkflowTest.php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Ligne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactureWorkflowTest extends TestCase
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

    public function test_creation_facture_depuis_devis()
    {
        $devis = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id,
            'statut' => 'accepte',
            'montant_ht' => 1000,
            'montant_ttc' => 1200
        ]);

        Ligne::factory()->create([
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id,
            'designation' => 'Service test',
            'quantite' => 10,
            'prix_unitaire' => 100,
            'tva' => 20
        ]);

        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/devis/{$devis->id}/convertir-facture");

        $response->assertRedirect();
        
        $this->assertDatabaseHas('factures', [
            'chantier_id' => $this->chantier->id,
            'devis_id' => $devis->id,
            'montant_ht' => 1000,
            'montant_ttc' => 1200,
            'statut' => 'envoyee',
            'montant_paye' => 0
        ]);

        $facture = Facture::where('devis_id', $devis->id)->first();
        $this->assertCount(1, $facture->lignes);
        $this->assertEquals('Service test', $facture->lignes->first()->designation);
    }

    public function test_creation_facture_manuelle()
    {
        $factureData = [
            'titre' => 'Facture Manuelle',
            'chantier_id' => $this->chantier->id,
            'lignes' => [
                [
                    'designation' => 'Prestation manuelle',
                    'quantite' => 5,
                    'prix_unitaire' => 200,
                    'tva' => 20
                ]
            ]
        ];

        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/factures", $factureData);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('factures', [
            'titre' => 'Facture Manuelle',
            'chantier_id' => $this->chantier->id,
            'montant_ht' => 1000,
            'montant_ttc' => 1200,
            'statut' => 'envoyee'
        ]);
    }

    public function test_ajout_paiement_complet()
    {
        $facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id,
            'montant_ttc' => 1200,
            'montant_paye' => 0,
            'statut' => 'envoyee'
        ]);

        $paiementData = [
            'montant' => 1200,
            'date_paiement' => '2025-07-01',
            'mode_paiement' => 'virement',
            'reference' => 'VIR123456'
        ];

        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/factures/{$facture->id}/paiement", $paiementData);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('paiements', [
            'facture_id' => $facture->id,
            'montant' => 1200,
            'mode_paiement' => 'virement'
        ]);

        $facture->refresh();
        $this->assertEquals(1200, $facture->montant_paye);
        $this->assertEquals('payee', $facture->statut);
    }

    public function test_ajout_paiements_partiels()
    {
        $facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id,
            'montant_ttc' => 1200,
            'montant_paye' => 0,
            'statut' => 'envoyee'
        ]);

        // Premier paiement partiel
        $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/factures/{$facture->id}/paiement", [
                'montant' => 800,
                'date_paiement' => '2025-07-01',
                'mode_paiement' => 'cheque'
            ]);

        $facture->refresh();
        $this->assertEquals(800, $facture->montant_paye);
        $this->assertEquals('partiellement_payee', $facture->statut);

        // Deuxième paiement pour compléter
        $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/factures/{$facture->id}/paiement", [
                'montant' => 400,
                'date_paiement' => '2025-07-15',
                'mode_paiement' => 'especes'
            ]);

        $facture->refresh();
        $this->assertEquals(1200, $facture->montant_paye);
        $this->assertEquals('payee', $facture->statut);
        $this->assertCount(2, $facture->paiements);
    }

    public function test_validation_paiement_superieur_montant_facture()
    {
        $facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'montant_ttc' => 1200,
            'montant_paye' => 0
        ]);

        $response = $this->actingAs($this->commercial)
            ->post("/chantiers/{$this->chantier->id}/factures/{$facture->id}/paiement", [
                'montant' => 1500, // Supérieur au montant de la facture
                'date_paiement' => '2025-07-01',
                'mode_paiement' => 'virement'
            ]);

        $response->assertSessionHasErrors(['montant']);
        $this->assertDatabaseMissing('paiements', [
            'facture_id' => $facture->id,
            'montant' => 1500
        ]);
    }

    public function test_client_ne_peut_pas_ajouter_paiement()
    {
        $facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'montant_ttc' => 1200
        ]);

        $response = $this->actingAs($this->client)
            ->post("/chantiers/{$this->chantier->id}/factures/{$facture->id}/paiement", [
                'montant' => 1200,
                'date_paiement' => '2025-07-01'
            ]);

        $response->assertStatus(403);
    }

    public function test_generation_numero_facture_automatique()
    {
        $facture1 = Facture::factory()->create([
            'chantier_id' => $this->chantier->id
        ]);
        
        $facture2 = Facture::factory()->create([
            'chantier_id' => $this->chantier->id
        ]);

        $this->assertNotEmpty($facture1->numero);
        $this->assertNotEmpty($facture2->numero);
        $this->assertNotEquals($facture1->numero, $facture2->numero);
    }

    public function test_calcul_reste_a_payer()
    {
        $facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'montant_ttc' => 1200,
            'montant_paye' => 800
        ]);

        $this->assertEquals(400, $facture->resteAPayer());
        $this->assertFalse($facture->estPayee());

        // Ajouter un paiement pour compléter
        Paiement::factory()->create([
            'facture_id' => $facture->id,
            'montant' => 400
        ]);

        $facture->refresh();
        $this->assertEquals(0, $facture->resteAPayer());
        $this->assertTrue($facture->estPayee());
    }

    public function test_export_facture_pdf()
    {
        $facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id
        ]);

        $response = $this->actingAs($this->commercial)
            ->get("/chantiers/{$this->chantier->id}/factures/{$facture->id}/pdf");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}

// tests/Feature/PermissionTest.php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $commercial1;
    private User $commercial2;
    private User $client1;
    private User $client2;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->commercial1 = User::factory()->create(['role' => 'commercial']);
        $this->commercial2 = User::factory()->create(['role' => 'commercial']);
        $this->client1 = User::factory()->create(['role' => 'client']);
        $this->client2 = User::factory()->create(['role' => 'client']);
    }

    public function test_admin_peut_tout_voir_et_modifier()
    {
        $chantier = Chantier::factory()->create([
            'client_id' => $this->client1->id,
            'commercial_id' => $this->commercial1->id
        ]);

        $devis = Devis::factory()->create([
            'chantier_id' => $chantier->id,
            'commercial_id' => $this->commercial1->id
        ]);

        $facture = Facture::factory()->create([
            'chantier_id' => $chantier->id,
            'commercial_id' => $this->commercial1->id
        ]);

        // Admin peut voir tous les chantiers
        $response = $this->actingAs($this->admin)->get('/chantiers');
        $response->assertOk()->assertSee($chantier->titre);

        // Admin peut voir le détail
        $response = $this->actingAs($this->admin)->get("/chantiers/{$chantier->id}");
        $response->assertOk();

        // Admin peut modifier
        $response = $this->actingAs($this->admin)
            ->get("/chantiers/{$chantier->id}/edit");
        $response->assertOk();

        // Admin peut créer devis et factures
        $response = $this->actingAs($this->admin)
            ->get("/chantiers/{$chantier->id}/devis/create");
        $response->assertOk();

        $response = $this->actingAs($this->admin)
            ->get("/chantiers/{$chantier->id}/factures/create");
        $response->assertOk();
    }

    public function test_commercial_acces_limite_a_ses_chantiers()
    {
        $sonChantier = Chantier::factory()->create([
            'client_id' => $this->client1->id,
            'commercial_id' => $this->commercial1->id
        ]);

        $autreChantier = Chantier::factory()->create([
            'client_id' => $this->client2->id,
            'commercial_id' => $this->commercial2->id
        ]);

        // Peut voir ses propres chantiers
        $response = $this->actingAs($this->commercial1)
            ->get("/chantiers/{$sonChantier->id}");
        $response->assertOk();

        // Ne peut pas voir les chantiers des autres
        $response = $this->actingAs($this->commercial1)
            ->get("/chantiers/{$autreChantier->id}");
        $response->assertStatus(403);

        // Peut modifier ses chantiers
        $response = $this->actingAs($this->commercial1)
            ->get("/chantiers/{$sonChantier->id}/edit");
        $response->assertOk();

        // Ne peut pas modifier les chantiers des autres
        $response = $this->actingAs($this->commercial1)
            ->get("/chantiers/{$autreChantier->id}/edit");
        $response->assertStatus(403);
    }

    public function test_client_acces_limite_a_ses_chantiers()
    {
        $sonChantier = Chantier::factory()->create([
            'client_id' => $this->client1->id,
            'commercial_id' => $this->commercial1->id
        ]);

        $autreChantier = Chantier::factory()->create([
            'client_id' => $this->client2->id,
            'commercial_id' => $this->commercial1->id
        ]);

        // Peut voir ses propres chantiers
        $response = $this->actingAs($this->client1)
            ->get("/chantiers/{$sonChantier->id}");
        $response->assertOk();

        // Ne peut pas voir les chantiers des autres
        $response = $this->actingAs($this->client1)
            ->get("/chantiers/{$autreChantier->id}");
        $response->assertStatus(403);

        // Ne peut jamais modifier
        $response = $this->actingAs($this->client1)
            ->get("/chantiers/{$sonChantier->id}/edit");
        $response->assertStatus(403);

        // Ne peut pas créer de chantiers
        $response = $this->actingAs($this->client1)
            ->get("/chantiers/create");
        $response->assertStatus(403);
    }

    public function test_permissions_devis_selon_role()
    {
        $chantier = Chantier::factory()->create([
            'client_id' => $this->client1->id,
            'commercial_id' => $this->commercial1->id
        ]);

        $devis = Devis::factory()->create([
            'chantier_id' => $chantier->id,
            'commercial_id' => $this->commercial1->id,
            'statut' => 'envoye'
        ]);

        // Commercial peut créer des devis
        $response = $this->actingAs($this->commercial1)
            ->post("/chantiers/{$chantier->id}/devis", [
                'titre' => 'Nouveau devis',
                'date_validite' => '2025-12-31'
            ]);
        $response->assertRedirect();

        // Client ne peut pas créer des devis
        $response = $this->actingAs($this->client1)
            ->post("/chantiers/{$chantier->id}/devis", [
                'titre' => 'Tentative client'
            ]);
        $response->assertStatus(403);

        // Client peut accepter un devis envoyé
        $response = $this->actingAs($this->client1)
            ->post("/chantiers/{$chantier->id}/devis/{$devis->id}/accepter");
        $response->assertRedirect();

        // Commercial d'un autre chantier ne peut pas modifier
        $response = $this->actingAs($this->commercial2)
            ->put("/chantiers/{$chantier->id}/devis/{$devis->id}", [
                'titre' => 'Modification non autorisée'
            ]);
        $response->assertStatus(403);
    }

    public function test_permissions_factures_selon_role()
    {
        $chantier = Chantier::factory()->create([
            'client_id' => $this->client1->id,
            'commercial_id' => $this->commercial1->id
        ]);

        $facture = Facture::factory()->create([
            'chantier_id' => $chantier->id,
            'commercial_id' => $this->commercial1->id,
            'montant_ttc' => 1200
        ]);

        // Commercial peut ajouter des paiements
        $response = $this->actingAs($this->commercial1)
            ->post("/chantiers/{$chantier->id}/factures/{$facture->id}/paiement", [
                'montant' => 1200,
                'date_paiement' => '2025-07-01',
                'mode_paiement' => 'virement'
            ]);
        $response->assertRedirect();

        // Client ne peut pas ajouter des paiements
        $response = $this->actingAs($this->client1)
            ->post("/chantiers/{$chantier->id}/factures/{$facture->id}/paiement", [
                'montant' => 1200,
                'date_paiement' => '2025-07-01'
            ]);
        $response->assertStatus(403);

        // Client peut voir ses factures
        $response = $this->actingAs($this->client1)
            ->get("/chantiers/{$chantier->id}/factures/{$facture->id}");
        $response->assertOk();

        // Autre client ne peut pas voir
        $response = $this->actingAs($this->client2)
            ->get("/chantiers/{$chantier->id}/factures/{$facture->id}");
        $response->assertStatus(403);
    }

    public function test_middleware_auth_sur_toutes_routes()
    {
        $chantier = Chantier::factory()->create();

        // Routes principales nécessitent authentification
        $this->get('/chantiers')->assertRedirect('/login');
        $this->get("/chantiers/{$chantier->id}")->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_middleware_role_admin()
    {
        // Routes admin uniquement accessibles aux admins
        $response = $this->actingAs($this->commercial1)->get('/admin');
        $response->assertStatus(403);

        $response = $this->actingAs($this->client1)->get('/admin');
        $response->assertStatus(403);

        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertOk();

        // Gestion des utilisateurs
        $response = $this->actingAs($this->commercial1)->get('/admin/users');
        $response->assertStatus(403);

        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertOk();
    }

    public function test_utilisateur_inactif_ne_peut_pas_acceder()
    {
        $userInactif = User::factory()->create([
            'role' => 'commercial',
            'active' => false
        ]);

        $chantier = Chantier::factory()->create();

        $response = $this->actingAs($userInactif)->get('/chantiers');
        $response->assertStatus(403);

        $response = $this->actingAs($userInactif)->get("/chantiers/{$chantier->id}");
        $response->assertStatus(403);
    }
}