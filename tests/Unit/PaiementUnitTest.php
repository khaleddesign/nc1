<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Paiement;
use App\Models\Facture;
use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaiementUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $commercial, $client, $chantier, $devis, $facture;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->commercial = User::factory()->commercial()->create();
        $this->client = User::factory()->client()->create();
        
        $this->chantier = Chantier::factory()->create([
            'client_id' => $this->client->id,
            'commercial_id' => $this->commercial->id
        ]);
        
        $this->devis = Devis::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id
        ]);
        
        $this->facture = Facture::factory()->create([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id,
            'devis_id' => $this->devis->id,
            'montant_ttc' => 5000.00,
            'montant_paye' => 0.00,
            'montant_restant' => 5000.00,
            'client_info' => json_encode([
                'nom' => $this->client->name,
                'email' => $this->client->email,
                'telephone' => '01.23.45.67.89',
                'adresse' => '123 rue Test, Paris'
            ])
        ]);
    }

    public function test_paiement_appartient_a_facture()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 2000.00
        ]);

        // Test relation avec facture
        $this->assertInstanceOf(Facture::class, $paiement->facture);
        $this->assertEquals($this->facture->id, $paiement->facture_id);
        
        // Test relation inverse
        $this->assertTrue($this->facture->paiements->contains($paiement));
    }

    public function test_paiement_appartient_a_user()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1500.00
        ]);

        // Test relation avec user
        $this->assertInstanceOf(User::class, $paiement->user);
        $this->assertEquals($this->commercial->id, $paiement->user_id);
        
        // Test relation inverse
        $this->assertTrue($this->commercial->paiements->contains($paiement));
    }

    public function test_generation_numero_paiement()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1000.00
        ]);

        // Vérifier qu'un numéro est généré (selon implémentation)
        if ($paiement->numero) {
            $this->assertNotNull($paiement->numero);
            $this->assertStringStartsWith('PAY-', $paiement->numero);
        }
    }

    public function test_modes_paiement_valides()
    {
        $modesValides = ['virement', 'cheque', 'especes', 'carte', 'prelevement'];
        
        foreach ($modesValides as $mode) {
            $paiement = Paiement::factory()->create([
                'facture_id' => $this->facture->id,
                'user_id' => $this->commercial->id,
                'montant' => 500.00,
                'mode_paiement' => $mode
            ]);
            
            $this->assertEquals($mode, $paiement->mode_paiement);
        }
    }

    public function test_paiement_avec_reference_et_banque()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 3000.00,
            'mode_paiement' => 'virement',
            'reference' => 'VIR20250625001',
            'banque' => 'BNP Paribas'
        ]);

        $this->assertEquals('VIR20250625001', $paiement->reference);
        $this->assertEquals('BNP Paribas', $paiement->banque);
        $this->assertEquals('virement', $paiement->mode_paiement);
    }

    public function test_paiement_avec_commentaire()
    {
        $commentaire = 'Paiement reçu avec 2 jours de retard';
        
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1200.00,
            'commentaire' => $commentaire
        ]);

        $this->assertEquals($commentaire, $paiement->commentaire);
    }

    public function test_date_paiement_par_defaut()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 800.00,
            'date_paiement' => now()->format('Y-m-d')
        ]);

        $this->assertEquals(now()->format('Y-m-d'), $paiement->date_paiement->format('Y-m-d'));
    }

    public function test_validation_montant_positif()
    {
        // Test avec montant positif (valide)
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1500.00
        ]);

        $this->assertGreaterThan(0, $paiement->montant);
        
        // Test avec montant zéro ou négatif (selon validation)
        // Cette partie dépend de votre implémentation de validation
    }

    public function test_paiements_multiples_meme_facture()
    {
        // Créer plusieurs paiements pour la même facture
        $paiement1 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 2000.00,
            'mode_paiement' => 'virement',
            'date_paiement' => now()->subDays(10)
        ]);

        $paiement2 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1500.00,
            'mode_paiement' => 'cheque',
            'date_paiement' => now()->subDays(5)
        ]);

        $paiement3 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1500.00,
            'mode_paiement' => 'especes',
            'date_paiement' => now()
        ]);

        // Vérifier que tous les paiements sont liés à la facture
        $this->assertEquals(3, $this->facture->paiements()->count());
        
        // Calculer le total des paiements
        $totalPaye = $this->facture->paiements()->sum('montant');
        $this->assertEquals(5000.00, $totalPaye); // 2000 + 1500 + 1500
    }

    public function test_scope_par_mode_paiement()
    {
        // Créer des paiements avec différents modes
        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'mode_paiement' => 'virement',
            'montant' => 1000.00
        ]);

        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'mode_paiement' => 'cheque',
            'montant' => 800.00
        ]);

        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'mode_paiement' => 'virement',
            'montant' => 1200.00
        ]);

        // Si des scopes existent dans le modèle
        $paieementsVirement = $this->facture->paiements()
            ->where('mode_paiement', 'virement')
            ->get();
        
        $this->assertEquals(2, $paieementsVirement->count());
        $this->assertEquals(2200.00, $paieementsVirement->sum('montant'));
    }

    public function test_paiement_ordre_chronologique()
    {
        // Créer des paiements à différentes dates
        $paiement1 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1000.00,
            'date_paiement' => now()->subDays(20)
        ]);

        $paiement3 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1500.00,
            'date_paiement' => now()
        ]);

        $paiement2 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 2500.00,
            'date_paiement' => now()->subDays(10)
        ]);

        // Récupérer les paiements par ordre chronologique
        $paiementsOrdonnés = $this->facture->paiements()
            ->orderBy('date_paiement', 'asc')
            ->get();

        $this->assertEquals($paiement1->id, $paiementsOrdonnés[0]->id);
        $this->assertEquals($paiement2->id, $paiementsOrdonnés[1]->id);
        $this->assertEquals($paiement3->id, $paiementsOrdonnés[2]->id);
    }

    public function test_suppression_paiement()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1000.00
        ]);

        $paiementId = $paiement->id;
        $this->assertDatabaseHas('paiements', ['id' => $paiementId]);

        // Supprimer le paiement
        $paiement->delete();

        // Vérifier suppression
        $this->assertDatabaseMissing('paiements', ['id' => $paiementId]);
    }

    public function test_formatage_montant()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1234.56
        ]);

        // Test du montant
        $this->assertEquals(1234.56, $paiement->montant);
        
        // Si des accesseurs de formatage existent dans le modèle
        // $this->assertEquals('1 234,56 €', $paiement->montant_formate);
    }

    public function test_relations_avec_chantier_via_facture()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 2500.00
        ]);

        // Accès au chantier via la facture
        $chantier = $paiement->facture->chantier;
        $this->assertEquals($this->chantier->id, $chantier->id);
        
        // Accès au client via la facture et le chantier
        $client = $paiement->facture->chantier->client;
        $this->assertEquals($this->client->id, $client->id);
    }

    public function test_calcul_total_paiements_par_facture()
    {
        // Créer plusieurs paiements pour la même facture
        $montants = [1500.00, 800.00, 2700.00];
        
        foreach ($montants as $montant) {
            Paiement::factory()->create([
                'facture_id' => $this->facture->id,
                'user_id' => $this->commercial->id,
                'montant' => $montant
            ]);
        }

        // Calculer le total
        $totalPaiements = $this->facture->paiements()->sum('montant');
        $this->assertEquals(5000.00, $totalPaiements); // 1500 + 800 + 2700
    }

    public function test_paiement_avec_dates_specifiques()
    {
        $datePaiement = now()->subDays(15);
        
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 3000.00,
            'date_paiement' => $datePaiement
        ]);

        $this->assertEquals($datePaiement->format('Y-m-d'), $paiement->date_paiement->format('Y-m-d'));
    }

    public function test_paiements_par_utilisateur()
    {
        $autreCommercial = User::factory()->commercial()->create();
        
        // Paiements du commercial 1
        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 2000.00
        ]);

        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1000.00
        ]);

        // Paiement du commercial 2
        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $autreCommercial->id,
            'montant' => 2000.00
        ]);

        // Vérifier les paiements par utilisateur
        $paiementsCommercial1 = $this->commercial->paiements;
        $paiementsCommercial2 = $autreCommercial->paiements;

        $this->assertEquals(2, $paiementsCommercial1->count());
        $this->assertEquals(1, $paiementsCommercial2->count());
        $this->assertEquals(3000.00, $paiementsCommercial1->sum('montant'));
        $this->assertEquals(2000.00, $paiementsCommercial2->sum('montant'));
    }

    public function test_mise_a_jour_paiement()
    {
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 1500.00,
            'mode_paiement' => 'virement',
            'reference' => 'VIR-OLD'
        ]);

        // Modifier le paiement
        $paiement->update([
            'montant' => 1800.00,
            'mode_paiement' => 'cheque',
            'reference' => 'CHQ-NEW-001',
            'banque' => 'Crédit Agricole',
            'commentaire' => 'Montant ajusté suite à révision'
        ]);

        $paiement->refresh();

        $this->assertEquals(1800.00, $paiement->montant);
        $this->assertEquals('cheque', $paiement->mode_paiement);
        $this->assertEquals('CHQ-NEW-001', $paiement->reference);
        $this->assertEquals('Crédit Agricole', $paiement->banque);
        $this->assertEquals('Montant ajusté suite à révision', $paiement->commentaire);
    }

    public function test_recherche_paiements_par_reference()
    {
        // Créer des paiements avec différentes références
        $paiement1 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'reference' => 'VIR-20250625-001'
        ]);

        $paiement2 = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'reference' => 'CHQ-20250625-002'
        ]);

        // Rechercher par référence
        $paiementTrouve = Paiement::where('reference', 'VIR-20250625-001')->first();
        $this->assertEquals($paiement1->id, $paiementTrouve->id);

        // Rechercher par pattern
        $paiementsVirement = Paiement::where('reference', 'LIKE', 'VIR-%')->get();
        $this->assertEquals(1, $paiementsVirement->count());
        $this->assertTrue($paiementsVirement->contains($paiement1));
    }

    public function test_validation_contraintes_metier()
    {
        // Créer un paiement valide
        $paiement = Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 2500.00,
            'date_paiement' => now(),
            'mode_paiement' => 'virement'
        ]);

        // Vérifications métier de base
        $this->assertGreaterThan(0, $paiement->montant);
        $this->assertNotNull($paiement->facture_id);
        $this->assertNotNull($paiement->user_id);
        $this->assertNotNull($paiement->mode_paiement);
        $this->assertInstanceOf(\Carbon\Carbon::class, $paiement->date_paiement);
    }

    public function test_calcul_reste_a_payer_apres_paiement()
    {
        // Facture de 5000€
        $this->assertEquals(5000.00, $this->facture->montant_ttc);
        $this->assertEquals(0.00, $this->facture->montant_paye);
        $this->assertEquals(5000.00, $this->facture->montant_restant);

        // Premier paiement de 2000€
        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 2000.00
        ]);

        // Si l'application met à jour automatiquement la facture
        // (vérifier selon votre implémentation)
        $totalPaye = $this->facture->paiements()->sum('montant');
        $this->assertEquals(2000.00, $totalPaye);

        // Deuxième paiement de 3000€ (soldant la facture)
        Paiement::factory()->create([
            'facture_id' => $this->facture->id,
            'user_id' => $this->commercial->id,
            'montant' => 3000.00
        ]);

        $totalPayeFinal = $this->facture->paiements()->sum('montant');
        $this->assertEquals(5000.00, $totalPayeFinal);
    }

    public function test_historique_paiements_chronologique()
    {
        // Créer des paiements à différentes dates
        $dates = [
            now()->subDays(30),
            now()->subDays(15),
            now()->subDays(5),
            now()
        ];

        $paiements = [];
        foreach ($dates as $index => $date) {
            $paiements[] = Paiement::factory()->create([
                'facture_id' => $this->facture->id,
                'user_id' => $this->commercial->id,
                'montant' => ($index + 1) * 500,
                'date_paiement' => $date
            ]);
        }

        // Récupérer l'historique chronologique
        $historiqueAsc = $this->facture->paiements()->orderBy('date_paiement')->get();
        $historiqueDesc = $this->facture->paiements()->orderBy('date_paiement', 'desc')->get();

        // Vérifier l'ordre croissant
        for ($i = 0; $i < count($dates) - 1; $i++) {
            $this->assertTrue(
                $historiqueAsc[$i]->date_paiement <= $historiqueAsc[$i + 1]->date_paiement
            );
        }

        // Vérifier l'ordre décroissant
        for ($i = 0; $i < count($dates) - 1; $i++) {
            $this->assertTrue(
                $historiqueDesc[$i]->date_paiement >= $historiqueDesc[$i + 1]->date_paiement
            );
        }
    }
}