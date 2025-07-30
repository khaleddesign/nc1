<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProspectService;
use App\Services\CalculService;
use App\Models\Devis;

class TestProspectService extends Command
{
    protected $signature = 'test:prospect-service';
    protected $description = 'Tester le ProspectService';

    public function handle()
    {
        $this->info('=== TEST PROSPECT SERVICE ===');

        try {
            // 1. Créer les services
            $calculService = new CalculService();
            $prospectService = new ProspectService($calculService);
            $this->info('✅ Services créés');

            // 2. Créer un prospect simple
            $prospect = $prospectService->creerProspect([
                'titre' => 'Test Prospect Service',
                'date_validite' => now()->addDays(30)->format('Y-m-d'),
                'client_nom' => 'Jean Dupont',
                'client_email' => 'jean.dupont@test.com',
                'lignes' => [
                    [
                        'designation' => 'Service Test',
                        'quantite' => 1,
                        'prix_unitaire_ht' => 100.00,
                    ]
                ]
            ]);

            $this->info("✅ Prospect créé: {$prospect->numero}");
            $this->info("   Client: {$prospect->client_info['nom']}");
            $this->info("   Statut: {$prospect->statut->label()}");
            $this->info("   Total: {$prospect->montant_ttc}€");

            // 3. Tester l'envoi
            $prospectService->envoyerProspect($prospect, ['envoyer_email' => false]);
            $prospect->refresh();
            $this->info("✅ Prospect envoyé - Statut: {$prospect->statut->label()}");

            // 4. Tester l'acceptation
            $prospectService->accepterProspect($prospect);
            $prospect->refresh();
            $this->info("✅ Prospect accepté - Statut: {$prospect->statut->label()}");

            // 5. Statistiques
            $stats = $prospectService->getStatistiquesProspects();
            $this->info("📊 Statistiques:");
            $this->info("   Total prospects: {$stats['total']}");
            $this->info("   Acceptés: {$stats['acceptes']}");
            $this->info("   Convertibles: {$stats['convertibles']}");
            $this->info("   Taux conversion: {$stats['taux_conversion']}%");

            $this->info('🎉 Tous les tests sont passés avec succès !');

        } catch (\Exception $e) {
            $this->error("❌ Erreur: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }
}