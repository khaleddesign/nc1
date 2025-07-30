<?php

// app/Console/Commands/FixDevisData.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixDevisData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'devis:fix-data {--dry-run : Afficher les changements sans les appliquer}';

    /**
     * The console command description.
     */
    protected $description = 'Corriger les données de la table devis après migration vers enum StatutDevis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 MODE DRY-RUN : Aucune modification ne sera appliquée');
        }
        
        $this->info('=== DIAGNOSTIC DE LA TABLE DEVIS ===');
        
        // 1. Vérifier l'état actuel de la table
        $this->checkTableStructure();
        
        // 2. Analyser les données actuelles
        $this->analyzeCurrentData();
        
        // 3. Effectuer la migration si nécessaire
        if ($this->hasOldColumns()) {
            $this->info('📝 Anciennes colonnes détectées - Migration nécessaire');
            if (!$isDryRun) {
                $this->migrateWithOldColumns();
            } else {
                $this->info('🔄 Migration qui serait effectuée :');
                $this->previewMigrationWithOldColumns();
            }
        } else {
            $this->info('🔄 Structure déjà migrée - Correction des données seulement');
            if (!$isDryRun) {
                $this->fixDataOnly();
            } else {
                $this->previewDataFix();
            }
        }
        
        // 4. Validation finale
        $this->validateResults($isDryRun);
        
        if ($isDryRun) {
            $this->warn('🚨 Aucune modification appliquée (dry-run)');
            $this->info('💡 Exécutez sans --dry-run pour appliquer les changements');
        } else {
            $this->info('✅ Correction terminée avec succès !');
        }
    }
    
    private function checkTableStructure()
    {
        $this->info('📋 Structure de la table devis :');
        
        $columns = DB::select("PRAGMA table_info(devis)");
        foreach ($columns as $col) {
            $this->line("  - {$col->name} ({$col->type})");
        }
    }
    
    private function analyzeCurrentData()
    {
        $this->info('📊 Données actuelles :');
        
        $total = DB::table('devis')->count();
        $this->line("  Total devis: {$total}");
        
        if ($total === 0) {
            $this->warn('  Aucun devis trouvé');
            return;
        }
        
        // Analyser les statuts actuels
        $statutsActuels = DB::table('devis')
            ->select('statut', DB::raw('count(*) as count'))
            ->groupBy('statut')
            ->get();
            
        foreach ($statutsActuels as $row) {
            $this->line("  Statut '{$row->statut}': {$row->count} devis");
        }
        
        // Si on a les anciennes colonnes, analyser aussi
        if ($this->hasOldColumns()) {
            $this->info('📋 Répartition par type (ancienne structure) :');
            
            $typesDevis = DB::table('devis')
                ->select('type_devis', DB::raw('count(*) as count'))
                ->groupBy('type_devis')
                ->get();
                
            foreach ($typesDevis as $row) {
                $this->line("  Type '{$row->type_devis}': {$row->count} devis");
            }
        }
    }
    
    private function hasOldColumns(): bool
    {
        return Schema::hasColumn('devis', 'type_devis') || 
               Schema::hasColumn('devis', 'statut_prospect');
    }
    
    private function migrateWithOldColumns()
    {
        $this->info('🔄 Migration complète avec anciennes colonnes...');
        
        // Sauvegarder d'abord
        $this->info('💾 Sauvegarde des données actuelles...');
        DB::update("
            UPDATE devis 
            SET backup_statuts = json('{\"type_devis\":\"' || COALESCE(type_devis, '') || '\",\"statut_old\":\"' || COALESCE(statut, '') || '\",\"statut_prospect\":\"' || COALESCE(statut_prospect, '') || '\"}')
            WHERE backup_statuts IS NULL
        ");
        
        // Migration des prospects
        $counts = [];
        $counts['prospect_brouillon'] = DB::update("
            UPDATE devis 
            SET statut = 'prospect_brouillon'
            WHERE type_devis = 'prospect' 
            AND (statut_prospect = 'brouillon' OR statut_prospect IS NULL)
        ");
        
        $counts['prospect_envoye'] = DB::update("
            UPDATE devis 
            SET statut = 'prospect_envoye'
            WHERE type_devis = 'prospect' 
            AND statut_prospect = 'envoye'
        ");
        
        $counts['prospect_negocie'] = DB::update("
            UPDATE devis 
            SET statut = 'prospect_negocie'
            WHERE type_devis = 'prospect' 
            AND statut_prospect = 'negocie'
        ");
        
        $counts['prospect_accepte'] = DB::update("
            UPDATE devis 
            SET statut = 'prospect_accepte'
            WHERE type_devis = 'prospect' 
            AND statut_prospect = 'accepte'
        ");
        
        // Migration des devis chantiers
        $counts['facture'] = DB::update("
            UPDATE devis 
            SET statut = 'facture'
            WHERE type_devis = 'chantier' 
            AND facture_id IS NOT NULL
        ");
        
        $counts['facturable'] = DB::update("
            UPDATE devis 
            SET statut = 'facturable'
            WHERE type_devis = 'chantier' 
            AND statut = 'accepte' 
            AND facture_id IS NULL
        ");
        
        $counts['chantier_valide'] = DB::update("
            UPDATE devis 
            SET statut = 'chantier_valide'
            WHERE type_devis = 'chantier' 
            AND statut IN ('brouillon', 'envoye')
        ");
        
        // Migration des prospects convertis
        $counts['chantier_valide'] += DB::update("
            UPDATE devis 
            SET statut = 'chantier_valide'
            WHERE type_devis = 'converti'
        ");
        
        // Afficher les résultats
        foreach ($counts as $statut => $count) {
            if ($count > 0) {
                $this->line("  ✅ {$statut}: {$count} devis migrés");
            }
        }
        
        // Supprimer les anciennes colonnes
        $this->info('🧹 Suppression des anciennes colonnes...');
        Schema::table('devis', function($table) {
            if (Schema::hasColumn('devis', 'type_devis')) {
                $table->dropColumn('type_devis');
            }
            if (Schema::hasColumn('devis', 'statut_prospect')) {
                $table->dropColumn('statut_prospect');
            }
        });
        
        $this->info('✅ Migration complète terminée');
    }
    
    private function previewMigrationWithOldColumns()
    {
        // Prévisualisation des changements
        $migrations = [
            ['prospect + brouillon/null', 'prospect_brouillon'],
            ['prospect + envoye', 'prospect_envoye'],
            ['prospect + negocie', 'prospect_negocie'],
            ['prospect + accepte', 'prospect_accepte'],
            ['chantier + facture_id', 'facture'],
            ['chantier + accepte', 'facturable'],
            ['chantier + brouillon/envoye', 'chantier_valide'],
            ['converti', 'chantier_valide'],
        ];
        
        foreach ($migrations as [$condition, $nouveauStatut]) {
            $this->line("  📝 {$condition} → {$nouveauStatut}");
        }
    }
    
    private function fixDataOnly()
    {
        $this->info('🔧 Correction des données seulement...');
        
        // Si on a que des statuts 'brouillon', 'envoye', etc. c'est probablement des chantiers
        $brouillonCount = DB::table('devis')->where('statut', 'brouillon')->count();
        if ($brouillonCount > 0) {
            $updated = DB::update("
                UPDATE devis 
                SET statut = 'chantier_valide'
                WHERE statut = 'brouillon'
            ");
            $this->line("  ✅ Statuts 'brouillon' → 'chantier_valide': {$updated} devis");
        }
        
        $envoyeCount = DB::table('devis')->where('statut', 'envoye')->count();
        if ($envoyeCount > 0) {
            $updated = DB::update("
                UPDATE devis 
                SET statut = 'chantier_valide'
                WHERE statut = 'envoye'
            ");
            $this->line("  ✅ Statuts 'envoye' → 'chantier_valide': {$updated} devis");
        }
        
        $accepteCount = DB::table('devis')->where('statut', 'accepte')->count();
        if ($accepteCount > 0) {
            $updated = DB::update("
                UPDATE devis 
                SET statut = 'facturable'
                WHERE statut = 'accepte' AND facture_id IS NULL
            ");
            $updated2 = DB::update("
                UPDATE devis 
                SET statut = 'facture'
                WHERE statut = 'accepte' AND facture_id IS NOT NULL
            ");
            $this->line("  ✅ Statuts 'accepte' → 'facturable': {$updated} devis");
            $this->line("  ✅ Statuts 'accepte' → 'facture': {$updated2} devis");
        }
    }
    
    private function previewDataFix()
    {
        $brouillonCount = DB::table('devis')->where('statut', 'brouillon')->count();
        $envoyeCount = DB::table('devis')->where('statut', 'envoye')->count();
        $accepteCount = DB::table('devis')->where('statut', 'accepte')->count();
        
        if ($brouillonCount > 0) {
            $this->line("  📝 'brouillon' → 'chantier_valide': {$brouillonCount} devis");
        }
        if ($envoyeCount > 0) {
            $this->line("  📝 'envoye' → 'chantier_valide': {$envoyeCount} devis");
        }
        if ($accepteCount > 0) {
            $facturable = DB::table('devis')->where('statut', 'accepte')->whereNull('facture_id')->count();
            $facture = DB::table('devis')->where('statut', 'accepte')->whereNotNull('facture_id')->count();
            $this->line("  📝 'accepte' → 'facturable': {$facturable} devis");
            $this->line("  📝 'accepte' → 'facture': {$facture} devis");
        }
    }
    
    private function validateResults($isDryRun)
    {
        $this->info('🔍 Validation finale :');
        
        if (!$isDryRun) {
            $finalData = DB::table('devis')
                ->select('statut', DB::raw('count(*) as count'))
                ->groupBy('statut')
                ->get();
                
            foreach ($finalData as $row) {
                $this->line("  ✅ Statut '{$row->statut}': {$row->count} devis");
            }
            
            // Test de l'enum
            try {
                $testDevis = \App\Models\Devis::first();
                if ($testDevis && $testDevis->statut) {
                    $this->line("  🎯 Test enum - Statut: " . $testDevis->statut->value);
                    $this->line("  🏷️  Label: " . $testDevis->statut->label());
                    $this->info('  ✅ L\'enum fonctionne correctement !');
                } else {
                    $this->warn('  ⚠️  Aucun devis trouvé pour tester l\'enum');
                }
            } catch (\Exception $e) {
                $this->error('  ❌ Erreur avec l\'enum: ' . $e->getMessage());
                $this->warn('  💡 Vérifiez que le modèle Devis a bien le cast StatutDevis::class');
            }
        }
    }
}