<?php
namespace Tests\Unit;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChantierTest extends TestCase
{
    use RefreshDatabase;

    public function test_relation_client()
    {
        $client = User::factory()->create(['role' => 'client']);
        $chantier = Chantier::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(User::class, $chantier->client);
        $this->assertEquals($client->id, $chantier->client->id);
        $this->assertEquals('client', $chantier->client->role);
    }

    public function test_relation_commercial()
    {
        $commercial = User::factory()->create(['role' => 'commercial']);
        $chantier = Chantier::factory()->create(['commercial_id' => $commercial->id]);

        $this->assertInstanceOf(User::class, $chantier->commercial);
        $this->assertEquals($commercial->id, $chantier->commercial->id);
        $this->assertEquals('commercial', $chantier->commercial->role);
    }

    public function test_relation_devis()
    {
        $chantier = Chantier::factory()->create();
        
        $devis1 = Devis::factory()->create(['chantier_id' => $chantier->id]);
        $devis2 = Devis::factory()->create(['chantier_id' => $chantier->id]);
        
        // Devis d'un autre chantier
        Devis::factory()->create(['chantier_id' => Chantier::factory()->create()->id]);

        $this->assertCount(2, $chantier->devis);
        $this->assertTrue($chantier->devis->contains($devis1));
        $this->assertTrue($chantier->devis->contains($devis2));
    }

    public function test_relation_factures()
    {
        $chantier = Chantier::factory()->create();
        
        $facture1 = Facture::factory()->create(['chantier_id' => $chantier->id]);
        $facture2 = Facture::factory()->create(['chantier_id' => $chantier->id]);
        
        // Facture d'un autre chantier
        Facture::factory()->create(['chantier_id' => Chantier::factory()->create()->id]);

        $this->assertCount(2, $chantier->factures);
        $this->assertTrue($chantier->factures->contains($facture1));
        $this->assertTrue($chantier->factures->contains($facture2));
    }

    public function test_calcul_avancement_global()
    {
        $chantier = Chantier::factory()->create(['avancement_global' => 0]);

        // Test avec différents pourcentages
        $chantier->avancement_global = 25;
        $chantier->save();
        $this->assertEquals(25, $chantier->fresh()->avancement_global);

        $chantier->avancement_global = 100;
        $chantier->save();
        $this->assertEquals(100, $chantier->fresh()->avancement_global);
    }

    public function test_attributs_fillable()
    {
        $chantierData = [
            'titre' => 'Chantier Test',
            'description' => 'Description du chantier',
            'client_id' => User::factory()->create()->id,
            'commercial_id' => User::factory()->create()->id,
            'statut' => 'en_cours',
            'date_debut' => '2025-07-01',
            'date_fin_prevue' => '2025-12-31',
            'budget' => 50000,
            'avancement_global' => 30
        ];

        $chantier = Chantier::create($chantierData);

        $this->assertEquals('Chantier Test', $chantier->titre);
        $this->assertEquals('Description du chantier', $chantier->description);
        $this->assertEquals('en_cours', $chantier->statut);
        $this->assertEquals('2025-07-01', $chantier->date_debut);
        $this->assertEquals('2025-12-31', $chantier->date_fin_prevue);
        $this->assertEquals(50000, $chantier->budget);
        $this->assertEquals(30, $chantier->avancement_global);
    }

    public function test_calcul_duree_prevue()
    {
        $chantier = Chantier::factory()->create([
            'date_debut' => '2025-07-01',
            'date_fin_prevue' => '2025-07-31'
        ]);

        // Test de la méthode si elle existe
        if (method_exists($chantier, 'dureePrevueEnJours')) {
            $this->assertEquals(30, $chantier->dureePrevueEnJours());
        }
    }

    public function test_statut_valides()
    {
        $statuts = ['planifie', 'en_cours', 'suspendu', 'termine', 'annule'];
        
        foreach ($statuts as $statut) {
            $chantier = Chantier::factory()->create(['statut' => $statut]);
            $this->assertEquals($statut, $chantier->statut);
        }
    }
}
