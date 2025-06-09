<?php
/**
 * Script de migration automatique Bootstrap vers Tailwind
 * Usage: php migrate-bootstrap-to-tailwind.php
 */

class BootstrapToTailwindMigrator
{
    private array $replacements = [
        // Containers
        'container-fluid' => 'w-full px-4',
        'container' => 'container mx-auto px-4',
        
        // Grid system
        'row' => 'flex flex-wrap',
        'col-12' => 'w-full',
        'col-11' => 'w-11/12',
        'col-10' => 'w-5/6',
        'col-9' => 'w-3/4',
        'col-8' => 'w-2/3',
        'col-6' => 'w-1/2',
        'col-4' => 'w-1/3',
        'col-3' => 'w-1/4',
        'col-2' => 'w-1/6',
        'col-1' => 'w-1/12',
        'col-md-12' => 'w-full md:w-full',
        'col-md-6' => 'w-full md:w-1/2',
        'col-md-4' => 'w-full md:w-1/3',
        'col-md-3' => 'w-full md:w-1/4',
        'col-lg-8' => 'w-full lg:w-2/3',
        'col-lg-6' => 'w-full lg:w-1/2',
        'col-lg-4' => 'w-full lg:w-1/3',
        'col-lg-3' => 'w-full lg:w-1/4',
        
        // Buttons
        'btn btn-primary' => 'btn btn-primary',
        'btn btn-secondary' => 'btn btn-secondary',
        'btn btn-success' => 'btn btn-success',
        'btn btn-danger' => 'btn btn-danger',
        'btn btn-warning' => 'btn btn-warning',
        'btn btn-outline-primary' => 'btn btn-outline',
        'btn btn-outline-secondary' => 'btn btn-outline',
        'btn btn-sm' => 'btn btn-sm',
        'btn btn-lg' => 'btn btn-lg',
        
        // Cards
        'card' => 'card',
        'card-header' => 'card-header',
        'card-body' => 'card-body',
        'card-footer' => 'card-footer',
        'card-title' => 'text-lg font-semibold text-gray-900',
        'card-text' => 'text-gray-600',
        
        // Forms
        'form-control' => 'form-input',
        'form-select' => 'form-select',
        'form-label' => 'form-label',
        'form-check' => 'flex items-center',
        'form-check-input' => 'form-checkbox',
        'is-invalid' => 'border-red-300 focus:border-red-500 focus:ring-red-500',
        'invalid-feedback' => 'form-error',
        
        // Alerts
        'alert alert-success' => 'alert alert-success',
        'alert alert-danger' => 'alert alert-error',
        'alert alert-warning' => 'alert alert-warning',
        'alert alert-info' => 'alert alert-info',
        
        // Tables
        'table' => 'table',
        'table-responsive' => 'overflow-x-auto',
        'table-hover' => 'table',
        
        // Badges
        'badge bg-primary' => 'badge badge-primary',
        'badge bg-secondary' => 'badge badge-secondary',
        'badge bg-success' => 'badge badge-success',
        'badge bg-danger' => 'badge badge-danger',
        'badge bg-warning' => 'badge badge-warning',
        
        // Text utilities
        'text-center' => 'text-center',
        'text-left' => 'text-left',
        'text-right' => 'text-right',
        'text-muted' => 'text-gray-500',
        'text-primary' => 'text-primary-600',
        'text-success' => 'text-success-600',
        'text-danger' => 'text-danger-600',
        'text-warning' => 'text-warning-600',
        
        // Spacing
        'mb-0' => 'mb-0',
        'mb-1' => 'mb-1',
        'mb-2' => 'mb-2',
        'mb-3' => 'mb-3',
        'mb-4' => 'mb-4',
        'mb-5' => 'mb-5',
        'mt-0' => 'mt-0',
        'mt-1' => 'mt-1',
        'mt-2' => 'mt-2',
        'mt-3' => 'mt-3',
        'mt-4' => 'mt-4',
        'mt-5' => 'mt-5',
        'p-0' => 'p-0',
        'p-1' => 'p-1',
        'p-2' => 'p-2',
        'p-3' => 'p-3',
        'p-4' => 'p-4',
        'p-5' => 'p-5',
        'py-1' => 'py-1',
        'py-2' => 'py-2',
        'py-3' => 'py-3',
        'py-4' => 'py-4',
        'py-5' => 'py-5',
        'px-1' => 'px-1',
        'px-2' => 'px-2',
        'px-3' => 'px-3',
        'px-4' => 'px-4',
        'px-5' => 'px-5',
        
        // Display
        'd-none' => 'hidden',
        'd-block' => 'block',
        'd-inline' => 'inline',
        'd-inline-block' => 'inline-block',
        'd-flex' => 'flex',
        'd-grid' => 'grid',
        
        // Flexbox
        'justify-content-center' => 'justify-center',
        'justify-content-between' => 'justify-between',
        'justify-content-end' => 'justify-end',
        'align-items-center' => 'items-center',
        'align-items-start' => 'items-start',
        'align-items-end' => 'items-end',
        
        // Width/Height
        'w-100' => 'w-full',
        'h-100' => 'h-full',
    ];

    private array $viewsToMigrate = [
        'resources/views/admin/statistics.blade.php',
        'resources/views/admin/users/create.blade.php',
        'resources/views/admin/users/edit.blade.php',
        'resources/views/admin/users/index.blade.php',
        'resources/views/chantiers/calendrier.blade.php',
        'resources/views/chantiers/create.blade.php',
        'resources/views/chantiers/edit.blade.php',
        'resources/views/chantiers/etapes.blade.php',
        'resources/views/chantiers/index.blade.php',
        'resources/views/chantiers/show.blade.php',
        'resources/views/dashboard/commercial.blade.php',
        'resources/views/notifications/index.blade.php',
    ];

    public function migrate(): void
    {
        echo "🚀 Début de la migration Bootstrap vers Tailwind\n";
        echo "================================================\n\n";

        foreach ($this->viewsToMigrate as $file) {
            $this->migrateFile($file);
        }

        echo "\n✅ Migration terminée !\n";
        echo "🔍 Vérifiez les fichiers modifiés et testez l'affichage.\n";
    }

    private function migrateFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            echo "⚠️  Fichier non trouvé : {$filePath}\n";
            return;
        }

        echo "📝 Migration de : {$filePath}\n";

        // Créer une sauvegarde
        $backupPath = $filePath . '.backup.' . date('Y-m-d-H-i-s');
        copy($filePath, $backupPath);
        echo "   💾 Sauvegarde créée : {$backupPath}\n";

        $content = file_get_contents($filePath);
        $originalContent = $content;
        $changesCount = 0;

        // Appliquer les remplacements
        foreach ($this->replacements as $bootstrap => $tailwind) {
            $pattern = '/class="([^"]*)\b' . preg_quote($bootstrap) . '\b([^"]*)"/';
            $replacement = 'class="$1' . $tailwind . '$2"';
            
            $newContent = preg_replace($pattern, $replacement, $content);
            if ($newContent !== $content) {
                $changesCount += substr_count($content, $bootstrap) - substr_count($newContent, $bootstrap);
                $content = $newContent;
            }
        }

        // Nettoyer les classes dupliquées et les espaces
        $content = preg_replace('/class="([^"]+)"/', function($matches) {
            $classes = preg_split('/\s+/', trim($matches[1]));
            $classes = array_unique(array_filter($classes));
            return 'class="' . implode(' ', $classes) . '"';
        }, $content);

        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            echo "   ✅ {$changesCount} remplacements effectués\n";
        } else {
            echo "   ℹ️  Aucune modification nécessaire\n";
            unlink($backupPath); // Supprimer la sauvegarde si pas de changement
        }

        echo "\n";
    }

    public function analyze(): void
    {
        echo "🔍 Analyse des fichiers Bootstrap restants\n";
        echo "==========================================\n\n";

        $bootstrapClasses = [
            'btn', 'container', 'row', 'col-', 'card', 'navbar', 'alert',
            'form-control', 'table', 'badge', 'd-flex', 'd-none', 'd-block'
        ];

        foreach ($this->viewsToMigrate as $file) {
            if (!file_exists($file)) continue;

            $content = file_get_contents($file);
            $foundClasses = [];

            foreach ($bootstrapClasses as $class) {
                if (strpos($content, $class) !== false) {
                    preg_match_all('/class="[^"]*' . preg_quote($class) . '[^"]*"/', $content, $matches);
                    if (!empty($matches[0])) {
                        $foundClasses = array_merge($foundClasses, $matches[0]);
                    }
                }
            }

            if (!empty($foundClasses)) {
                echo "📄 {$file}\n";
                foreach (array_unique($foundClasses) as $class) {
                    echo "   - {$class}\n";
                }
                echo "\n";
            }
        }
    }

    public function generateReport(): void
    {
        echo "📊 Génération du rapport de migration\n";
        echo "=====================================\n\n";

        $report = [
            'total_files' => count($this->viewsToMigrate),
            'migrated_files' => 0,
            'remaining_bootstrap' => [],
            'backup_files' => []
        ];

        foreach ($this->viewsToMigrate as $file) {
            if (!file_exists($file)) continue;

            // Chercher les fichiers de sauvegarde
            $backupFiles = glob($file . '.backup.*');
            if (!empty($backupFiles)) {
                $report['migrated_files']++;
                $report['backup_files'] = array_merge($report['backup_files'], $backupFiles);
            }

            // Analyser le contenu restant
            $content = file_get_contents($file);
            if (preg_match_all('/class="[^"]*\b(btn|container|row|col-|card|navbar|alert|form-control|table|badge|d-flex|d-none|d-block)\b[^"]*"/', $content, $matches)) {
                $report['remaining_bootstrap'][$file] = array_unique($matches[0]);
            }
        }

        echo "Fichiers total : {$report['total_files']}\n";
        echo "Fichiers migrés : {$report['migrated_files']}\n";
        echo "Sauvegardes créées : " . count($report['backup_files']) . "\n\n";

        if (!empty($report['remaining_bootstrap'])) {
            echo "⚠️  Classes Bootstrap restantes :\n";
            foreach ($report['remaining_bootstrap'] as $file => $classes) {
                echo "   📄 {$file}\n";
                foreach ($classes as $class) {
                    echo "      - {$class}\n";
                }
                echo "\n";
            }
        } else {
            echo "✅ Aucune classe Bootstrap détectée !\n";
        }
    }
}

// Exécution du script
if ($argc > 1) {
    $migrator = new BootstrapToTailwindMigrator();
    
    switch ($argv[1]) {
        case 'analyze':
            $migrator->analyze();
            break;
        case 'migrate':
            $migrator->migrate();
            break;
        case 'report':
            $migrator->generateReport();
            break;
        default:
            echo "Usage: php {$argv[0]} [analyze|migrate|report]\n";
            echo "  analyze  - Analyser les classes Bootstrap restantes\n";
            echo "  migrate  - Migrer les fichiers vers Tailwind\n";
            echo "  report   - Générer un rapport de migration\n";
    }
} else {
    echo "Usage: php {$argv[0]} [analyze|migrate|report]\n";
}