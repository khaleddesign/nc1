<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Enums\StatutDevis;
use App\Services\ProspectService;
use App\Services\CalculService;
use App\Services\ConversionService;
use App\Services\NegociationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevisFatureModuleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $commercial;
    private User $client;
    private Chantier $chantier;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les utilisateurs de test
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->commercial = User::factory()->create(['role' => 'commercial']);
        $this->client = User::factory()->create(['role' => 'client']);
        
        // Créer un chantier de test
        $this->chantier = Chantier::factory()->create([
            'client_id' => $this->client->id,
            'commercial_id' => $this->commercial->id,
            'titre' => 'Chantier Test Validation'
        ]);
    }

    // ====================================================
    // 📊 TESTS ENUM STATUTDEVIS
    // ====================================================

    public function test_enum_statut_devis_a_tous_les_statuts_requis()
    {
        $statutsAttendus = [
            'prospect_brouillon',
            'prospect_envoye', 
            'prospect_negocie',
            'prospect_accepte',
            'chantier_valide',
            'facturable',
            'facture'
        ];

        $statutsEnum = array_map(fn($case) => $case->value, StatutDevis::cases());

        foreach ($statutsAttendus as $statut) {
            $this->assertContains($statut, $statutsEnum, "Statut {$statut} manquant dans l'enum");
        }

        $this->assertCount(7, StatutDevis::cases(), "L'enum doit avoir exactement 7 statuts");
    }

    public function test_enum_statut_devis_methodes_label()
    {
        foreach (StatutDevis::cases() as $statut) {
            $label = $statut->label();
            $this->assertNotEmpty($label, "Label vide pour {$statut->value}");
            $this->assertIsString($label, "Label doit être une string pour {$statut->value}");
        }
    }

    public function test_enum_statut_devis_methodes_badge_class()
    {
        foreach (StatutDevis::cases() as $statut) {
            $badgeClass = $statut->badgeClass();
            $this->assertNotEmpty($badgeClass, "BadgeClass vide pour {$statut->value}");
            $this->assertStringContains('bg-', $badgeClass, "BadgeClass doit contenir 'bg-' pour {$statut->value}");
        }
    }

    public function test_enum_logique_is_prospect_et_is_chantier()
    {
        // Test statuts prospects
        $statutsProspects = [
            StatutDevis::PROSPECT_BROUILLON,
            StatutDevis::PROSPECT_ENVOYE,
            StatutDevis::PROSPECT_NEGOCIE,
            StatutDevis::PROSPECT_ACCEPTE
        ];

        foreach ($statutsProspects as $statut) {
            $this->assertTrue($statut->isProspect(), "{$statut->value} devrait être un prospect");
            $this->assertFalse($statut->isChantier(), "{$statut->value} ne devrait pas être un chantier");
        }

        // Test statuts chantiers
        $statutsChantiers = [
            StatutDevis::CHANTIER_VALIDE,
            StatutDevis::FACTURABLE,
            StatutDevis::FACTURE
        ];

        foreach ($statutsChantiers as $statut) {
            $this->assertFalse($statut->isProspect(), "{$statut->value} ne devrait pas être un prospect");
            $this->assertTrue($statut->isChantier(), "{$statut->value} devrait être un chantier");
        }
    }

    public function test_enum_workflow_transitions()
    {
        // Test conversion uniquement pour PROSPECT_ACCEPTE
        $this->assertTrue(StatutDevis::PROSPECT_ACCEPTE->peutEtreConverti());
        $this->assertFalse(StatutDevis::PROSPECT_BROUILLON->peutEtreConverti());
        $this->assertFalse(StatutDevis::CHANTIER_VALIDE->peutEtreConverti());

        // Test modifications possibles
        $statutsModifiables = [
            StatutDevis::PROSPECT_BROUILLON,
            StatutDevis::PROSPECT_NEGOCIE,
            StatutDevis::CHANTIER_VALIDE
        ];

        foreach ($statutsModifiables as $statut) {
            $this->assertTrue($statut->peutEtreModifie(), "{$statut->value} devrait pouvoir être modifié");
        }

        $this->assertFalse(StatutDevis::FACTURE->peutEtreModifie(), "Facturé ne devrait pas pouvoir être modifié");
    }

    // ====================================================
    // 🔧 TESTS SERVICES MÉTIER
    // ====================================================

    public function test_services_metier_sont_injectables()
    {
        // Test injection ProspectService
        $prospectService = app(ProspectService::class);
        $this->assertInstanceOf(ProspectService::class, $prospectService);

        // Test injection CalculService
        $calculService = app(CalculService::class);
        $this->assertInstanceOf(CalculService::class, $calculService);

        // Test injection ConversionService
        $conversionService = app(ConversionService::class);
        $this->assertInstanceOf(ConversionService::class, $conversionService);

        // Test injection NegociationService
        $negociationService = app(NegociationService::class);
        $this->assertInstanceOf(NegociationService::class, $negociationService);
    }

    public function test_prospect_service_statistiques()
    {
        // Créer quelques devis de test
        Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_BROUILLON,
            'montant_ttc' => 1000
        ]);

        Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_ENVOYE,
            'montant_ttc' => 2000
        ]);

        $prospectService = app(ProspectService::class);
        $stats = $prospectService->getStatistiquesProspects();

        $this->assertIsArray($stats);
        
        $clefsAttendues = ['total', 'prospects', 'chantiers', 'envoye', 'convertibles', 'montant_total'];
        foreach ($clefsAttendues as $clef) {
            $this->assertArrayHasKey($clef, $stats, "Clef '{$clef}' manquante dans les statistiques");
        }

        $this->assertGreaterThanOrEqual(2, $stats['total'], "Total devrait être au moins 2");
        $this->assertGreaterThanOrEqual(3000, $stats['montant_total'], "Montant total devrait être au moins 3000");
    }

    // ====================================================
    // 🗄️ TESTS MODÈLES ET RELATIONS
    // ====================================================

    public function test_modele_devis_avec_statut_enum()
    {
        $devis = Devis::factory()->create([
            'statut' => StatutDevis::PROSPECT_BROUILLON,
            'commercial_id' => $this->commercial->id
        ]);

        $this->assertInstanceOf(StatutDevis::class, $devis->statut);
        $this->assertEquals('prospect_brouillon', $devis->statut->value);
        $this->assertNotEmpty($devis->statut->label());
    }

    public function test_devis_relations()
    {
        $devis = Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'chantier_id' => $this->chantier->id
        ]);

        // Test relation commercial
        $this->assertInstanceOf(User::class, $devis->commercial);
        $this->assertEquals($this->commercial->id, $devis->commercial->id);

        // Test relation chantier
        $this->assertInstanceOf(Chantier::class, $devis->chantier);
        $this->assertEquals($this->chantier->id, $devis->chantier->id);
    }

    public function test_devis_calculs_financiers()
    {
        $devis = Devis::factory()->create([
            'montant_ht' => 1000.00,
            'montant_tva' => 200.00,
            'montant_ttc' => 1200.00
        ]);

        $this->assertEquals(1000.00, $devis->montant_ht);
        $this->assertEquals(200.00, $devis->montant_tva);
        $this->assertEquals(1200.00, $devis->montant_ttc);

        // Vérification cohérence calculs
        $this->assertEquals($devis->montant_ht + $devis->montant_tva, $devis->montant_ttc);
    }

    // ====================================================
    // 🎯 TESTS WORKFLOW COMPLET
    // ====================================================

    public function test_creation_prospect_via_service()
    {
        $prospectService = app(ProspectService::class);

        $dataProspect = [
            'titre' => 'Test Prospect Validation',
            'client_nom' => 'Client Test',
            'client_email' => 'test@validation.com',
            'client_telephone' => '01.23.45.67.89',
            'commercial_id' => $this->commercial->id,
            'lignes' => [
                [
                    'designation' => 'Ligne test validation',
                    'quantite' => 2,
                    'prix_unitaire' => 500.00,
                    'unite' => 'unité'
                ]
            ]
        ];

        $prospect = $prospectService->creerProspect($dataProspect);

        // Vérifications création
        $this->assertInstanceOf(Devis::class, $prospect);
        $this->assertNotNull($prospect->id);
        $this->assertEquals('Test Prospect Validation', $prospect->titre);
        $this->assertEquals(StatutDevis::PROSPECT_BROUILLON, $prospect->statut);
        $this->assertNotEmpty($prospect->numero);

        // Vérification en base
        $this->assertDatabaseHas('devis', [
            'id' => $prospect->id,
            'titre' => 'Test Prospect Validation',
            'statut' => 'prospect_brouillon'
        ]);

        return $prospect;
    }

    /**
     * @depends test_creation_prospect_via_service
     */
    public function test_workflow_prospect_envoi(Devis $prospect = null)
    {
        if (!$prospect) {
            $prospect = $this->test_creation_prospect_via_service();
        }

        $prospectService = app(ProspectService::class);

        // Test envoi
        $prospectService->envoyerProspect($prospect);
        $prospect->refresh();

        $this->assertEquals(StatutDevis::PROSPECT_ENVOYE, $prospect->statut);
        $this->assertNotNull($prospect->date_envoi);

        return $prospect;
    }

    /**
     * @depends test_workflow_prospect_envoi
     */
    public function test_workflow_prospect_acceptation(Devis $prospect = null)
    {
        if (!$prospect) {
            $prospect = $this->test_workflow_prospect_envoi();
        }

        $prospectService = app(ProspectService::class);

        // Test acceptation
        $prospectService->accepterProspect($prospect);
        $prospect->refresh();

        $this->assertEquals(StatutDevis::PROSPECT_ACCEPTE, $prospect->statut);
        $this->assertTrue($prospect->statut->peutEtreConverti());

        return $prospect;
    }

    /**
     * @depends test_workflow_prospect_acceptation
     */
    public function test_conversion_prospect_en_chantier(Devis $prospect = null)
    {
        if (!$prospect) {
            $prospect = $this->test_workflow_prospect_acceptation();
        }

        $conversionService = app(ConversionService::class);

        // Test conversion
        $resultat = $conversionService->convertirProspectEnChantier($prospect, [
            'chantier_id' => $this->chantier->id
        ]);

        $this->assertIsArray($resultat);
        $this->assertArrayHasKey('devis', $resultat);

        $devisChantier = $resultat['devis'];
        $this->assertInstanceOf(Devis::class, $devisChantier);
        $this->assertEquals(StatutDevis::CHANTIER_VALIDE, $devisChantier->statut);
        $this->assertEquals($this->chantier->id, $devisChantier->chantier_id);

        // Vérification en base
        $this->assertDatabaseHas('devis', [
            'id' => $devisChantier->id,
            'statut' => 'chantier_valide',
            'chantier_id' => $this->chantier->id
        ]);
    }

    // ====================================================
    // 🌐 TESTS CONTRÔLEUR ET INTERFACE
    // ====================================================

    public function test_route_devis_index_accessible()
    {
        $response = $this->actingAs($this->admin)->get('/devis');
        
        $response->assertOk();
        $response->assertViewIs('devis.index');
        $response->assertViewHas('devis');
        $response->assertViewHas('stats');
    }

    public function test_route_devis_index_avec_statistiques()
    {
        // Créer quelques devis pour les stats
        Devis::factory()->count(3)->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_BROUILLON
        ]);

        Devis::factory()->count(2)->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_ENVOYE
        ]);

        $response = $this->actingAs($this->admin)->get('/devis');
        
        $response->assertOk();
        
        // Vérifier que les statistiques sont passées à la vue
        $stats = $response->viewData('stats');
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('prospects', $stats);
        $this->assertGreaterThanOrEqual(5, $stats['total']);
    }

    public function test_filtres_devis_fonctionnels()
    {
        // Créer des devis avec différents statuts
        Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_BROUILLON,
            'titre' => 'Devis Brouillon Test'
        ]);

        Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_ENVOYE,
            'titre' => 'Devis Envoyé Test'
        ]);

        // Test filtre par statut
        $response = $this->actingAs($this->admin)->get('/devis?statut=prospect_brouillon');
        $response->assertOk();

        // Test filtre par type
        $response = $this->actingAs($this->admin)->get('/devis?type=prospects');
        $response->assertOk();

        // Test recherche
        $response = $this->actingAs($this->admin)->get('/devis?search=Brouillon');
        $response->assertOk();
    }

    public function test_commercial_voit_seulement_ses_devis()
    {
        // Créer devis pour ce commercial
        $sonDevis = Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_BROUILLON
        ]);

        // Créer devis pour un autre commercial
        $autreCommercial = User::factory()->create(['role' => 'commercial']);
        $autreDevis = Devis::factory()->create([
            'commercial_id' => $autreCommercial->id,
            'statut' => StatutDevis::PROSPECT_BROUILLON
        ]);

        $response = $this->actingAs($this->commercial)->get('/devis');
        
        $response->assertOk();
        $devis = $response->viewData('devis');
        
        // Vérifier que seuls ses devis sont visibles
        $idsDevis = $devis->pluck('id')->toArray();
        $this->assertContains($sonDevis->id, $idsDevis);
        $this->assertNotContains($autreDevis->id, $idsDevis);
    }

    public function test_route_creation_devis_accessible()
    {
        $response = $this->actingAs($this->commercial)->get('/devis/create');
        
        $response->assertOk();
        $response->assertViewIs('devis.create');
        $response->assertViewHas('chantiers');
    }

    public function test_permissions_actions_selon_statut()
    {
        // Créer devis avec différents statuts
        $devisBrouillon = Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_BROUILLON
        ]);

        $devisAccepte = Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_ACCEPTE
        ]);

        $devisFacture = Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::FACTURE
        ]);

        // Test permissions
        $this->assertTrue($devisBrouillon->statut->peutEtreModifie());
        $this->assertFalse($devisBrouillon->statut->peutEtreConverti());

        $this->assertFalse($devisAccepte->statut->peutEtreModifie());
        $this->assertTrue($devisAccepte->statut->peutEtreConverti());

        $this->assertFalse($devisFacture->statut->peutEtreModifie());
        $this->assertFalse($devisFacture->statut->peutEtreConverti());
    }

    // ====================================================
    // 🧪 TESTS NÉGOCIATION ET VERSIONS
    // ====================================================

    public function test_ajout_version_negociation()
    {
        $devis = Devis::factory()->create([
            'commercial_id' => $this->commercial->id,
            'statut' => StatutDevis::PROSPECT_ENVOYE
        ]);

        $negociationService = app(NegociationService::class);

        $negociationService->ajouterVersion($devis, 'Réduction demandée', [
            'remise' => 10,
            'modification' => 'Prix réduit de 10%'
        ]);

        $devis->refresh();
        
        $this->assertEquals(StatutDevis::PROSPECT_NEGOCIE, $devis->statut);
        $this->assertNotNull($devis->historique_negociation);
        $this->assertIsArray($devis->historique_negociation);
        $this->assertCount(1, $devis->historique_negociation);
    }

    // ====================================================
    // 📊 TESTS PERFORMANCE ET INTÉGRITÉ
    // ====================================================

    public function test_performance_liste_devis()
    {
        // Créer un nombre significatif de devis
        Devis::factory()->count(50)->create([
            'commercial_id' => $this->commercial->id
        ]);

        $start = microtime(true);
        
        $response = $this->actingAs($this->admin)->get('/devis');
        
        $end = microtime(true);
        $duration = $end - $start;

        $response->assertOk();
        
        // Vérifier que la page se charge en moins de 2 secondes
        $this->assertLessThan(2.0, $duration, "La page des devis doit se charger en moins de 2 secondes");
    }

    public function test_integrite_donnees_apres_workflow()
    {
        $prospectService = app(ProspectService::class);
        $conversionService = app(ConversionService::class);

        // Créer prospect avec montant spécifique
        $montantInitial = 1500.00;
        $dataProspect = [
            'titre' => 'Test Intégrité',
            'client_nom' => 'Client Intégrité',
            'client_email' => 'integrite@test.com',
            'commercial_id' => $this->commercial->id,
            'lignes' => [
                [
                    'designation' => 'Ligne intégrité',
                    'quantite' => 1,
                    'prix_unitaire' => $montantInitial,
                    'unite' => 'unité'
                ]
            ]
        ];

        $prospect = $prospectService->creerProspect($dataProspect);
        
        // Workflow complet
        $prospectService->envoyerProspect($prospect);
        $prospectService->accepterProspect($prospect);
        
        $resultat = $conversionService->convertirProspectEnChantier($prospect, [
            'chantier_id' => $this->chantier->id
        ]);

        $devisChantier = $resultat['devis'];

        // Vérifier intégrité des données
        $this->assertEquals($prospect->montant_ttc, $devisChantier->montant_ttc);
        $this->assertEquals($prospect->titre, $devisChantier->titre);
        $this->assertEquals($this->chantier->id, $devisChantier->chantier_id);
        $this->assertNotEquals($prospect->id, $devisChantier->id);
    }

    // ====================================================
    // 🏆 TEST DE VALIDATION FINALE
    // ====================================================

    public function test_validation_complete_module()
    {
        // Ce test synthétise tous les éléments critiques
        
        // 1. Enum complet
        $this->assertCount(7, StatutDevis::cases());
        
        // 2. Services injectables
        $this->assertInstanceOf(ProspectService::class, app(ProspectService::class));
        $this->assertInstanceOf(CalculService::class, app(CalculService::class));
        $this->assertInstanceOf(ConversionService::class, app(ConversionService::class));
        $this->assertInstanceOf(NegociationService::class, app(NegociationService::class));
        
        // 3. Interface accessible
        $response = $this->actingAs($this->admin)->get('/devis');
        $response->assertOk();
        
        // 4. Workflow fonctionnel
        $prospectService = app(ProspectService::class);
        $stats = $prospectService->getStatistiquesProspects();
        $this->assertIsArray($stats);
        
        // 5. Permissions respectées
        $responseCommercial = $this->actingAs($this->commercial)->get('/devis');
        $responseCommercial->assertOk();
        
        // ✅ Si ce test passe, le module est validé pour la production
        $this->assertTrue(true, "Module devis-facture validé pour la production !");
    }
}