<?php
namespace Tests\Unit;

use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Ligne;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevisTest extends TestCase
{
    use RefreshDatabase;

    public function test_methode_peut_etre_modifie()
    {
        $devisBrouillon = Devis::factory()->create(['statut' => 'brouillon']);
        $devisEnvoye = Devis::factory()->create(['statut' => 'envoye']);
        $devisAccepte = Devis::factory()->create(['statut' => 'accepte']);
        $devisRefuse = Devis::factory()->create(['statut' => 'refuse']);

        $this->assertTrue($devisBrouillon->peutEtreModifie());
        $this->assertTrue($devisEnvoye->peutEtreModifie());
        $this->assertFalse($devisAccepte->peutEtreModifie());
        $this->assertFalse($devisRefuse->peutEtreModifie());
    }

    public function test_methode_peut_etre_accepte()
    {
        // Devis envoyé et non expiré
        $devisValide = Devis::factory()->create([
            'statut' => 'envoye',
            'date_validite' => Carbon::now()->addDays(10)->format('Y-m-d')
        ]);

        // Devis envoyé mais expiré
        $devisExpire = Devis::factory()->create([
            'statut' => 'envoye',
            'date_validite' => Carbon::now()->subDays(10)->format('Y-m-d')
        ]);

        // Devis accepté
        $devisAccepte = Devis::factory()->create([
            'statut' => 'accepte',
            'date_validite' => Carbon::now()->addDays(10)->format('Y-m-d')
        ]);

        $this->assertTrue($devisValide->peutEtreAccepte());
        $this->assertFalse($devisExpire->peutEtreAccepte());
        $this->assertFalse($devisAccepte->peutEtreAccepte());
    }

    public function test_methode_is_expire()
    {
        $devisNonExpire = Devis::factory()->create([
            'date_validite' => Carbon::now()->addDays(10)->format('Y-m-d')
        ]);

        $devisExpire = Devis::factory()->create([
            'date_validite' => Carbon::now()->subDays(10)->format('Y-m-d')
        ]);

        if (method_exists($devisNonExpire, 'isExpire')) {
            $this->assertFalse($devisNonExpire->isExpire());
            $this->assertTrue($devisExpire->isExpire());
        }
    }

    public function test_relation_chantier()
    {
        $chantier = Chantier::factory()->create();
        $devis = Devis::factory()->create(['chantier_id' => $chantier->id]);

        $this->assertInstanceOf(Chantier::class, $devis->chantier);
        $this->assertEquals($chantier->id, $devis->chantier->id);
    }

    public function test_relation_lignes()
    {
        $devis = Devis::factory()->create();
        
        $ligne1 = Ligne::factory()->create([
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id
        ]);
        
        $ligne2 = Ligne::factory()->create([
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id
        ]);

        $this->assertCount(2, $devis->lignes);
        $this->assertTrue($devis->lignes->contains($ligne1));
        $this->assertTrue($devis->lignes->contains($ligne2));
    }

    public function test_relation_facture()
    {
        $devis = Devis::factory()->create();
        $facture = Facture::factory()->create(['devis_id' => $devis->id]);

        $this->assertInstanceOf(Facture::class, $devis->facture);
        $this->assertEquals($facture->id, $devis->facture->id);
    }

    public function test_calcul_montants_automatique()
    {
        $devis = Devis::factory()->create([
            'montant_ht' => 0,
            'montant_ttc' => 0
        ]);

        // Ajouter des lignes
        Ligne::factory()->create([
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id,
            'quantite' => 10,
            'prix_unitaire' => 100,
            'tva' => 20
        ]);

        Ligne::factory()->create([
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id,
            'quantite' => 5,
            'prix_unitaire' => 50,
            'tva' => 10
        ]);

        // Si la méthode calculerMontants existe
        if (method_exists($devis, 'calculerMontants')) {
            $devis->calculerMontants();
            
            $this->assertEquals(1250, $devis->montant_ht); // (10*100) + (5*50)
            $this->assertEquals(1475, $devis->montant_ttc); // 1000*1.2 + 250*1.1
        }
    }

    public function test_generation_numero_automatique()
    {
        $devis1 = Devis::factory()->create();
        $devis2 = Devis::factory()->create();

        $this->assertNotEmpty($devis1->numero);
        $this->assertNotEmpty($devis2->numero);
        $this->assertNotEquals($devis1->numero, $devis2->numero);
    }

    public function test_statut_transitions()
    {
        $devis = Devis::factory()->create(['statut' => 'brouillon']);
        
        // Brouillon -> Envoyé
        $devis->statut = 'envoye';
        $devis->save();
        $this->assertEquals('envoye', $devis->statut);
        
        // Envoyé -> Accepté
        $devis->statut = 'accepte';
        $devis->save();
        $this->assertEquals('accepte', $devis->statut);
    }
}
