<?php

// database/migrations/2025_07_01_add_facturation_electronique_to_factures.php
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
        Schema::table('factures', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà
            if (!Schema::hasColumn('factures', 'donnees_structurees')) {
                $table->json('donnees_structurees')->nullable()->after('notes_internes');
            }
            if (!Schema::hasColumn('factures', 'format_electronique')) {
                $table->string('format_electronique')->nullable()->after('donnees_structurees');
            }
            if (!Schema::hasColumn('factures', 'hash_integrite')) {
                $table->string('hash_integrite')->nullable()->after('format_electronique');
            }
            if (!Schema::hasColumn('factures', 'conforme_loi')) {
                $table->boolean('conforme_loi')->default(false)->after('hash_integrite');
            }
            if (!Schema::hasColumn('factures', 'date_transmission')) {
                $table->timestamp('date_transmission')->nullable()->after('conforme_loi');
            }
            if (!Schema::hasColumn('factures', 'numero_chronologique')) {
                $table->string('numero_chronologique')->nullable()->after('date_transmission');
            }
        });

        // Ajouter les index seulement s'ils n'existent pas
        $this->addIndexesSafely();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            // Supprimer les index en premier (SQLite safe)
            $this->dropIndexesSafely($table);
            
            // Supprimer les colonnes seulement si elles existent
            $columnsToCheck = [
                'numero_chronologique',
                'date_transmission', 
                'conforme_loi',
                'hash_integrite',
                'format_electronique',
                'donnees_structurees'
            ];
            
            $existingColumns = [];
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('factures', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }

    /**
     * Ajouter les index de manière sécurisée
     */
    private function addIndexesSafely(): void
    {
        try {
            // Vérifier si l'index n'existe pas déjà avant de l'ajouter
            if (!$this->indexExists('factures', 'factures_conforme_loi_date_emission_index')) {
                Schema::table('factures', function (Blueprint $table) {
                    $table->index(['conforme_loi', 'date_emission'], 'factures_conforme_loi_date_emission_index');
                });
            }
            
            if (!$this->indexExists('factures', 'factures_date_transmission_index')) {
                Schema::table('factures', function (Blueprint $table) {
                    $table->index('date_transmission');
                });
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs d'index en cas de conflit
            \Log::warning('Erreur lors de la création des index factures: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer les index de manière sécurisée
     */
    private function dropIndexesSafely(Blueprint $table): void
    {
        try {
            if ($this->indexExists('factures', 'factures_conforme_loi_date_emission_index')) {
                $table->dropIndex('factures_conforme_loi_date_emission_index');
            }
            if ($this->indexExists('factures', 'factures_date_transmission_index')) {
                $table->dropIndex(['date_transmission']);
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs lors de la suppression des index
            \Log::warning('Erreur lors de la suppression des index factures: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si un index existe (compatible SQLite)
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $driver = Schema::getConnection()->getDriverName();
            
            if ($driver === 'sqlite') {
                $indexes = Schema::getConnection()
                    ->select("PRAGMA index_list({$table})");
                return collect($indexes)->contains('name', $indexName);
            } else {
                // MySQL/PostgreSQL
                $indexes = Schema::getConnection()
                    ->select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
                return !empty($indexes);
            }
        } catch (\Exception $e) {
            return false;
        }
    }
};