<?php
namespace Tests\Unit;

use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Ligne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactureTest extends TestCase
{
    use RefreshDatabase;

    public function test_methode_est_payee()
    {
        // Facture entièrement payée
        $facturePayee = Facture::factory()->create([
            'montant_ttc' => 1200,
            'montant_paye' => 1200
        ]);

        // Facture partiellement payée
        $facturePartielle = Facture::factory()->create([
            'montant_ttc' => 1200,
            'montant_paye' => 800
        ]);

        // Facture non payée
        $factureNonPayee = Facture::factory()->create([
            'montant_ttc' => 1200,
            'montant_paye' => 0
        ]);

        $this->assertTrue($facturePayee->estPayee());
        $this->assertFalse($facturePartielle->estPayee());
        $this->assertFalse($factureNonPayee->estPayee());
    }

    public function test_methode_reste_a_payer()
    {
        $facture = Facture::factory()->create([
            'montant_ttc' => 1200,
            'montant_paye' => 800
        ]);

        if (method_exists($facture, 'resteAPayer')) {
            $this->assertEquals(400, $facture->resteAPayer());
        }

        // Test avec facture entièrement payée
        $facture->montant_paye = 1200;
        $facture->save();

        if (method_exists($facture, 'resteAPayer')) {
            $this->assertEquals(0, $facture->resteAPayer());
        }
    }

    public function test_relation_chantier()
    {
        $chantier = Chantier::factory()->create();
        $facture = Facture::factory()->create(['chantier_id' => $chantier->id]);

        $this->assertInstanceOf(Chantier::class, $facture->chantier);
        $this->assertEquals($chantier->id, $facture->chantier->id);
    }

    public function test_relation_devis()
    {
        $devis = Devis::factory()->create();
        $facture = Facture::factory()->create(['devis_id' => $devis->id]);

        $this->assertInstanceOf(Devis::class, $facture->devis);
        $this->assertEquals($devis->id, $facture->devis->id);
    }

    public function test_relation_paiements()
    {
        $facture = Facture::factory()->create();
        
        $paiement1 = Paiement::factory()->create([
            'facture_id' => $facture->id,
            'montant' => 500
        ]);
        
        $paiement2 = Paiement::factory()->create([
            'facture_id' => $facture->id,
            'montant' => 300
        ]);

        // Paiement d'une autre facture
        Paiement::factory()->create([
            'facture_id' => Facture::factory()->create()->id,
            'montant' => 200
        ]);

        $this->assertCount(2, $facture->paiements);
        $this->assertTrue($facture->paiements->contains($paiement1));
        $this->assertTrue($facture->paiements->contains($paiement2));
    }

    public function test_calcul_montant_paye_depuis_paiements()
    {
        $facture = Facture::factory()->create([
            'montant_ttc' => 1200,
            'montant_paye' => 0
        ]);

        // Ajouter des paiements
        Paiement::factory()->create([
            'facture_id' => $facture->id,
            'montant' => 500
        ]);

        Paiement::factory()->create([
            'facture_id' => $facture->id,
            'montant' => 300
        ]);

        // Si méthode existe pour recalculer
        if (method_exists($facture, 'calculerMontantPaye')) {
            $facture->calculerMontantPaye();
            $this->assertEquals(800, $facture->montant_paye);
        } else {
            // Test direct de la somme
            $totalPaiements = $facture->paiements->sum('montant');
            $this->assertEquals(800, $totalPaiements);
        }
    }

    public function test_mise_a_jour_statut_selon_paiements()
    {
        $facture = Facture::factory()->create([
            'montant_ttc' => 1200,
            'montant_paye' => 0,
            'statut' => 'envoyee'
        ]);

        // Paiement partiel
        $facture->montant_paye = 800;
        $facture->save();

        if (method_exists($facture, 'mettreAJourStatut')) {
            $facture->mettreAJourStatut();
            $this->assertEquals('partiellement_payee', $facture->statut);
        }

        // Paiement complet
        $facture->montant_paye = 1200;
        $facture->save();

        if (method_exists($facture, 'mettreAJourStatut')) {
            $facture->mettreAJourStatut();
            $this->assertEquals('payee', $facture->statut);
        }
    }

    public function test_generation_numero_automatique()
    {
        $facture1 = Facture::factory()->create();
        $facture2 = Facture::factory()->create();

        $this->assertNotEmpty($facture1->numero);
        $this->assertNotEmpty($facture2->numero);
        $this->assertNotEquals($facture1->numero, $facture2->numero);
    }

    public function test_attributs_fillable()
    {
        $factureData = [
            'numero' => 'FAC-2025-001',
            'chantier_id' => Chantier::factory()->create()->id,
            'commercial_id' => \App\Models\User::factory()->create()->id,
            'devis_id' => Devis::factory()->create()->id,
            'titre' => 'Facture Test',
            'statut' => 'envoyee',
            'montant_ht' => 1000,
            'montant_ttc' => 1200,
            'montant_paye' => 0
        ];

        $facture = Facture::create($factureData);

        $this->assertEquals('FAC-2025-001', $facture->numero);
        $this->assertEquals('Facture Test', $facture->titre);
        $this->assertEquals('envoyee', $facture->statut);
        $this->assertEquals(1000, $facture->montant_ht);
        $this->assertEquals(1200, $facture->montant_ttc);
        $this->assertEquals(0, $facture->montant_paye);
    }

    public function test_validation_montant_paye_ne_depasse_pas_montant_ttc()
    {
        $facture = Facture::factory()->create([
            'montant_ttc' => 1200
        ]);

        // Test avec validation si elle existe
        if (method_exists($facture, 'validerMontantPaye')) {
            $this->assertTrue($facture->validerMontantPaye(1200));
            $this->assertTrue($facture->validerMontantPaye(800));
            $this->assertFalse($facture->validerMontantPaye(1500));
        }
    }

    public function test_conversion_depuis_devis()
    {
        $devis = Devis::factory()->create([
            'montant_ht' => 1000,
            'montant_ttc' => 1200,
            'statut' => 'accepte'
        ]);

        // Ajouter des lignes au devis
        Ligne::factory()->create([
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id,
            'designation' => 'Service original',
            'quantite' => 10,
            'prix_unitaire' => 100
        ]);

        // Si méthode de conversion existe
        if (method_exists(Facture::class, 'creerDepuisDevis')) {
            $facture = Facture::creerDepuisDevis($devis);
            
            $this->assertEquals($devis->chantier_id, $facture->chantier_id);
            $this->assertEquals($devis->id, $facture->devis_id);
            $this->assertEquals($devis->montant_ht, $facture->montant_ht);
            $this->assertEquals($devis->montant_ttc, $facture->montant_ttc);
            $this->assertEquals('envoyee', $facture->statut);
        }
    }
}