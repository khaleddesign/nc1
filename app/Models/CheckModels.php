<?php
// Fichier à placer dans : app/Console/Commands/CheckModels.php
// Ou à exécuter via php artisan tinker

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class CheckModels extends Command
{
    protected $signature = 'btp:check-models';
    protected $description = 'Vérifie la structure des modèles BTP existants';

    public function handle()
    {
        $this->info('🔍 VÉRIFICATION DES MODÈLES LARAVEL BTP');
        $this->info('======================================');
        
        // 1. Vérifier les modèles existants
        $this->checkModels();
        
        // 2. Vérifier les tables en base
        $this->checkTables();
        
        // 3. Vérifier les relations possibles
        $this->checkRelations();
        
        // 4. Recommandations pour les tests
        $this->generateTestRecommendations();
    }
    
    private function checkModels()
    {
        $this->info("\n📁 MODÈLES DISPONIBLES :");
        $this->info("------------------------");
        
        $modelsPath = app_path('Models');
        if (!File::exists($modelsPath)) {
            $this->error("❌ Dossier app/Models/ introuvable");
            return;
        }
        
        $models = File::files($modelsPath);
        foreach ($models as $model) {
            $modelName = pathinfo($model->getFilename(), PATHINFO_FILENAME);
            $this->info("✅ {$modelName}.php");
            
            // Vérifier si le modèle est instanciable
            $className = "App\\Models\\{$modelName}";
            if (class_exists($className)) {
                $this->line("   → Classe {$className} trouvée");
            } else {
                $this->warn("   ⚠️ Classe {$className} non trouvée");
            }
        }
    }
    
    private function checkTables()
    {
        $this->info("\n🗂️ TABLES EN BASE DE DONNÉES :");
        $this->info("-----------------------------");
        
        $tables = ['users', 'chantiers', 'devis', 'factures', 'paiements', 'lignes', 'messages', 'notifications'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("✅ Table '{$table}' existe");
                $columns = Schema::getColumnListing($table);
                $this->line("   Colonnes: " . implode(', ', $columns));
            } else {
                $this->warn("❌ Table '{$table}' n'existe pas");
            }
        }
    }
    
    private function checkRelations()
    {
        $this->info("\n🔗 VÉRIFICATION DES RELATIONS :");
        $this->info("------------------------------");
        
        // Vérifier User
        if (class_exists('App\Models\User')) {
            $user = new \App\Models\User();
            $this->info("✅ User - Méthodes disponibles :");
            $methods = get_class_methods($user);
            $relationMethods = array_filter($methods, function($method) {
                return in_array($method, ['chantiers', 'chantiersCommercial', 'chantiersClient', 'devis', 'factures']);
            });
            foreach ($relationMethods as $method) {
                $this->line("   → {$method}()");
            }
        }
        
        // Vérifier Chantier
        if (class_exists('App\Models\Chantier')) {
            $this->info("✅ Chantier - Relations potentielles :");
            $this->line("   → client() (belongsTo User)");
            $this->line("   → commercial() (belongsTo User)");
            $this->line("   → devis() (hasMany)");
            $this->line("   → factures() (hasMany)");
        }
    }
    
    private function generateTestRecommendations()
    {
        $this->info("\n🎯 RECOMMANDATIONS POUR LES TESTS :");
        $this->info("----------------------------------");
        
        $this->info("Basé sur la structure détectée, créer :");
        $this->line("1. Tests Unit pour chaque modèle existant");
        $this->line("2. Tests Feature pour les routes confirmées");
        $this->line("3. Factory pour chaque modèle avec colonnes réelles");
        $this->line("4. Tests de permissions selon les rôles User");
        
        $this->warn("\n⚠️ NE PAS tester ce qui n'existe pas encore !");
    }
}

// OU version simplifiée pour Tinker :
// 
// use Illuminate\Support\Facades\Schema;
// 
// echo "🔍 VÉRIFICATION RAPIDE\n";
// echo "===================\n";
// 
// $tables = ['users', 'chantiers', 'devis', 'factures', 'paiements'];
// foreach ($tables as $table) {
//     if (Schema::hasTable($table)) {
//         echo "✅ {$table}: " . implode(', ', Schema::getColumnListing($table)) . "\n";
//     } else {
//         echo "❌ {$table}: n'existe pas\n";
//     }
// }
// 
// echo "\nModèles détectés:\n";
// $models = ['User', 'Chantier', 'Devis', 'Facture', 'Paiement'];
// foreach ($models as $model) {
//     $class = "App\\Models\\{$model}";
//     echo class_exists($class) ? "✅ {$model}\n" : "❌ {$model}\n";
// }