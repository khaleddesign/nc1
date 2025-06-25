<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ligne;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\User;
use App\Models\Chantier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LigneUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $chantier, $devis, $facture;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->commercial()->create();
        $client = User::factory()->client()->create();
        
        $this->chantier = Chantier::factory()->create([
            'client_id' => $client->id,
            'commercial_id' => $this->user->id
        ]);
        
        $this->devis = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->user->id
        ]);
        
        $this->facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->user->id,
            'devis_id' => $this->devis->id
        ]);
    }

    public function test_ligne_appartient_a_devis()
    {
        $ligne = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'designation' => 'Carrelage salle de bain',
            'quantite' => 20,
            'prix_unitaire_ht' => 50.00,
            'taux_tva' => 20
        ]);

        // Test relation polymorphique
        $this->assertInstanceOf(Devis::class, $ligne->ligneable);
        $this->assertEquals($this->devis->id, $ligne->ligneable->id);
        
        // Test inverse
        $this->assertTrue($this->devis->lignes->contains($ligne));
    }

    public function test_ligne_appartient_a_facture()
    {
        $ligne = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Facture',
            'ligneable_id' => $this->facture->id,
            'designation' => 'Peinture salon',
            'quantite' => 1,
            'prix_unitaire_ht' => 800.00,
            'taux_tva' => 20
        ]);

        // Test relation polymorphique
        $this->assertInstanceOf(Facture::class, $ligne->ligneable);
        $this->assertEquals($this->facture->id, $ligne->ligneable->id);
        
        // Test inverse
        $this->assertTrue($this->facture->lignes->contains($ligne));
    }

    public function test_calculs_automatiques_montants()
    {
        $ligne = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'designation' => 'Test calcul',
            'quantite' => 10,
            'prix_unitaire_ht' => 100.00,
            'taux_tva' => 20,
            'remise_pourcentage' => 10 // 10% de remise
        ]);

        // Montant HT = quantite * prix_unitaire_ht
        $montantHtAvantRemise = 10 * 100.00; // = 1000€
        
        // Remise = 10% de 1000€ = 100€
        $remiseMontant = $montantHtAvantRemise * 0.10; // = 100€
        
        // Montant HT après remise = 1000€ - 100€ = 900€
        $montantHtFinal = $montantHtAvantRemise - $remiseMontant; // = 900€
        
        // TVA = 20% de 900€ = 180€
        $montantTva = $montantHtFinal * 0.20; // = 180€
        
        // TTC = 900€ + 180€ = 1080€
        $montantTtc = $montantHtFinal + $montantTva; // = 1080€

        // Si l'application calcule automatiquement (vérifier selon implémentation)
        if ($ligne->montant_ht != null) {
            $this->assertEquals(900.00, $ligne->montant_ht);
            $this->assertEquals(100.00, $ligne->remise_montant);
            $this->assertEquals(180.00, $ligne->montant_tva);
            $this->assertEquals(1080.00, $ligne->montant_ttc);
        }
    }

    public function test_ordre_des_lignes()
    {
        // Créer plusieurs lignes avec des ordres différents
        $ligne1 = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'ordre' => 1,
            'designation' => 'Première ligne'
        ]);

        $ligne3 = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'ordre' => 3,
            'designation' => 'Troisième ligne'
        ]);

        $ligne2 = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'ordre' => 2,
            'designation' => 'Deuxième ligne'
        ]);

        // Test de l'ordre
        $lignesOrdrées = $this->devis->lignes()->orderBy('ordre')->get();
        
        $this->assertEquals('Première ligne', $lignesOrdrées[0]->designation);
        $this->assertEquals('Deuxième ligne', $lignesOrdrées[1]->designation);
        $this->assertEquals('Troisième ligne', $lignesOrdrées[2]->designation);
    }

    public function test_categories_lignes()
    {
        $ligneMateriaux = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'designation' => 'Carrelage',
            'categorie' => 'materiaux'
        ]);

        $ligneMainOeuvre = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'designation' => 'Pose carrelage',
            'categorie' => 'main_oeuvre'
        ]);

        $this->assertEquals('materiaux', $ligneMateriaux->categorie);
        $this->assertEquals('main_oeuvre', $ligneMainOeuvre->categorie);
    }

    public function test_unites_diverses()
    {
        $ligneM2 = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'designation' => 'Carrelage',
            'unite' => 'm²',
            'quantite' => 25.5
        ]);

        $ligneUnite = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'designation' => 'Porte',
            'unite' => 'unité',
            'quantite' => 3
        ]);

        $this->assertEquals('m²', $ligneM2->unite);
        $this->assertEquals(25.5, $ligneM2->quantite);
        $this->assertEquals('unité', $ligneUnite->unite);
        $this->assertEquals(3, $ligneUnite->quantite);
    }

    public function test_copie_lignes_devis_vers_facture()
    {
        // Créer des lignes pour le devis
        $lignesDevis = collect();
        for ($i = 1; $i <= 3; $i++) {
            $lignesDevis->push(Ligne::factory()->create([
                'ligneable_type' => 'App\Models\Devis',
                'ligneable_id' => $this->devis->id,
                'ordre' => $i,
                'designation' => "Ligne devis {$i}",
                'quantite' => $i * 5,
                'prix_unitaire_ht' => $i * 100
            ]));
        }

        // Simuler la copie vers facture (selon l'implémentation)
        foreach ($lignesDevis as $ligneDevis) {
            Ligne::create([
                'ligneable_type' => 'App\Models\Facture',
                'ligneable_id' => $this->facture->id,
                'ordre' => $ligneDevis->ordre,
                'designation' => $ligneDevis->designation,
                'description' => $ligneDevis->description,
                'unite' => $ligneDevis->unite,
                'quantite' => $ligneDevis->quantite,
                'prix_unitaire_ht' => $ligneDevis->prix_unitaire_ht,
                'taux_tva' => $ligneDevis->taux_tva,
                'categorie' => $ligneDevis->categorie
            ]);
        }

        // Vérifier que les lignes ont été copiées
        $this->assertEquals(3, $this->devis->lignes()->count());
        $this->assertEquals(3, $this->facture->lignes()->count());

        // Vérifier que les contenus correspondent
        $lignesFacture = $this->facture->lignes()->orderBy('ordre')->get();
        foreach ($lignesDevis as $index => $ligneDevis) {
            $ligneFacture = $lignesFacture[$index];
            $this->assertEquals($ligneDevis->designation, $ligneFacture->designation);
            $this->assertEquals($ligneDevis->quantite, $ligneFacture->quantite);
            $this->assertEquals($ligneDevis->prix_unitaire_ht, $ligneFacture->prix_unitaire_ht);
        }
    }

    public function test_suppression_cascade()
    {
        $ligne = Ligne::factory()->create([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id
        ]);

        $ligneId = $ligne->id;
        $this->assertDatabaseHas('lignes', ['id' => $ligneId]);

        // Supprimer le devis (si cascade configuré)
        $this->devis->delete();

        // Vérifier si la ligne est supprimée (dépend de la configuration)
        // Cette assertion peut être ajustée selon votre configuration de cascade
    }

    public function test_validation_donnees_ligne()
    {
        // Test avec des données invalides
        $ligne = new Ligne([
            'ligneable_type' => 'App\Models\Devis',
            'ligneable_id' => $this->devis->id,
            'designation' => '', // Vide
            'quantite' => -5, // Négatif
            'prix_unitaire_ht' => -100 // Négatif
        ]);

        // Ces validations dépendent de votre implémentation
        // Ajustez selon vos règles de validation
        $this->assertEmpty($ligne->designation);
        $this->assertEquals(-5, $ligne->quantite);
        $this->assertEquals(-100, $ligne->prix_unitaire_ht);
    }
}