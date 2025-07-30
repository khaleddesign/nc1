<?php

// database/migrations/2025_07_04_150000_add_flux_support_to_devis_table.php
// VERSION CORRIGÉE COMPATIBLE SQLITE

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('devis', 'chantier_converti_id')) {
                $table->unsignedBigInteger('chantier_converti_id')->nullable()->after('facture_id');
            }
            if (!Schema::hasColumn('devis', 'date_conversion')) {
                $table->timestamp('date_conversion')->nullable()->after('chantier_converti_id');
            }
            if (!Schema::hasColumn('devis', 'historique_negociation')) {
                $table->json('historique_negociation')->nullable()->after('date_conversion');
            }
            if (!Schema::hasColumn('devis', 'reference_externe')) {
                $table->string('reference_externe')->nullable()->after('historique_negociation');
            }
            
            // Colonnes facturation électronique
            if (!Schema::hasColumn('devis', 'donnees_structurees')) {
                $table->json('donnees_structurees')->nullable()->after('reference_externe');
            }
            if (!Schema::hasColumn('devis', 'format_electronique')) {
                $table->string('format_electronique')->nullable()->after('donnees_structurees');
            }
            if (!Schema::hasColumn('devis', 'hash_integrite')) {
                $table->string('hash_integrite')->nullable()->after('format_electronique');
            }
            if (!Schema::hasColumn('devis', 'conforme_loi')) {
                $table->boolean('conforme_loi')->default(false)->after('hash_integrite');
            }
            if (!Schema::hasColumn('devis', 'date_transmission')) {
                $table->timestamp('date_transmission')->nullable()->after('conforme_loi');
            }
            if (!Schema::hasColumn('devis', 'numero_chronologique')) {
                $table->string('numero_chronologique')->nullable()->after('date_transmission');
            }
        });

        // Ajouter les contraintes et index de manière sécurisée
        $this->addConstraintsSafely();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            // Supprimer les contraintes en premier
            $this->dropConstraintsSafely($table);
            
            // Supprimer les colonnes seulement si elles existent
            $columnsToCheck = [
                'numero_chronologique',
                'date_transmission',
                'conforme_loi', 
                'hash_integrite',
                'format_electronique',
                'donnees_structurees',
                'reference_externe',
                'historique_negociation',
                'date_conversion',
                'chantier_converti_id'
            ];
            
            $existingColumns = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('devis', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }

    /**
     * Ajouter les contraintes de manière sécurisée
     */
    private function addConstraintsSafely(): void
    {
        try {
            Schema::table('devis', function (Blueprint $table) {
                // Foreign key seulement si la table chantiers existe et la colonne n'a pas déjà la contrainte
                if (Schema::hasTable('chantiers') && Schema::hasColumn('devis', 'chantier_converti_id')) {
                    $table->foreign('chantier_converti_id')->references('id')->on('chantiers')->onDelete('set null');
                }
            });

            // Index de performance
            if (!$this->indexExists('devis', 'devis_chantier_converti_id_index')) {
                Schema::table('devis', function (Blueprint $table) {
                    $table->index('chantier_converti_id');
                });
            }
            
            if (!$this->indexExists('devis', 'devis_conforme_loi_index')) {
                Schema::table('devis', function (Blueprint $table) {
                    $table->index('conforme_loi');
                });
            }
            
        } catch (\Exception $e) {
            \Log::warning('Erreur lors de l\'ajout des contraintes devis: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer les contraintes de manière sécurisée
     */
    private function dropConstraintsSafely(Blueprint $table): void
    {
        try {
            // Supprimer la foreign key si elle existe
            if ($this->foreignKeyExists('devis', 'devis_chantier_converti_id_foreign')) {
                $table->dropForeign(['chantier_converti_id']);
            }
            
            // Supprimer les index
            if ($this->indexExists('devis', 'devis_chantier_converti_id_index')) {
                $table->dropIndex(['chantier_converti_id']);
            }
            if ($this->indexExists('devis', 'devis_conforme_loi_index')) {
                $table->dropIndex(['conforme_loi']);
            }
            
        } catch (\Exception $e) {
            \Log::warning('Erreur lors de la suppression des contraintes devis: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si un index existe
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $driver = Schema::getConnection()->getDriverName();
            
            if ($driver === 'sqlite') {
                $indexes = Schema::getConnection()->select("PRAGMA index_list({$table})");
                return collect($indexes)->contains('name', $indexName);
            } else {
                $indexes = Schema::getConnection()->select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
                return !empty($indexes);
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Vérifier si une foreign key existe
     */
    private function foreignKeyExists(string $table, string $foreignKeyName): bool
    {
        try {
            $driver = Schema::getConnection()->getDriverName();
            
            if ($driver === 'sqlite') {
                $foreignKeys = Schema::getConnection()->select("PRAGMA foreign_key_list({$table})");
                return !empty($foreignKeys);
            } else {
                $constraints = Schema::getConnection()->select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ?
                ", [$table, $foreignKeyName]);
                return !empty($constraints);
            }
        } catch (\Exception $e) {
            return false;
        }
    }
};