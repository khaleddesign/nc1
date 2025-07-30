<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ajouter la nouvelle colonne statut_unifie
        Schema::table('devis', function (Blueprint $table) {
            $table->string('statut_unifie')->nullable()->after('statut_prospect');
            $table->timestamp('date_conversion_chantier')->nullable()->after('converted_at');
            $table->json('historique_statuts')->nullable()->after('date_conversion_chantier');
            
            // Backup des anciennes colonnes dans un JSON temporaire
            $table->json('backup_statuts')->nullable()->after('historique_statuts');
        });

        // 2. Sauvegarder les anciennes données et migrer
        $this->migrateExistingData();

        // 3. Supprimer les anciennes colonnes et renommer la nouvelle
        Schema::table('devis', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            $table->dropColumn(['type_devis', 'statut', 'statut_prospect']);
            
            // Renommer la nouvelle colonne
            $table->renameColumn('statut_unifie', 'statut');
            
            // Ajouter les index pour les performances
            $table->index('statut');
            $table->index(['statut', 'commercial_id']);
            $table->index(['statut', 'chantier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Rétablir les anciennes colonnes
        Schema::table('devis', function (Blueprint $table) {
            $table->string('type_devis')->default('chantier')->after('commercial_id');
            $table->string('statut_old')->default('brouillon')->after('type_devis');
            $table->string('statut_prospect')->nullable()->after('statut_old');
            $table->string('statut_ancien')->nullable()->after('backup_statuts');
        });

        // 2. Renommer la colonne actuelle
        Schema::table('devis', function (Blueprint $table) {
            $table->renameColumn('statut', 'statut_ancien');
        });

        // 3. Restaurer les données depuis la sauvegarde
        $this->restoreOriginalData();

        // 4. Nettoyer les colonnes temporaires
        Schema::table('devis', function (Blueprint $table) {
            $table->renameColumn('statut_old', 'statut');
            $table->dropColumn([
                'statut_ancien',
                'date_conversion_chantier', 
                'historique_statuts',
                'backup_statuts'
            ]);
            
            // Rétablir les index originaux
            $table->index(['type_devis', 'commercial_id']);
            $table->index(['statut', 'commercial_id']);
        });
    }

    /**
     * Migrer les données existantes vers le nouveau format (Compatible SQLite/MySQL)
     */
    private function migrateExistingData(): void
    {
        $dateNow = now()->toDateTimeString();
        $driver = DB::getDriverName();

        // Sauvegarder les anciennes valeurs (compatible multi-base)
        if ($driver === 'sqlite') {
            // SQLite : Utiliser une approche simplifiée
            DB::update("
                UPDATE devis 
                SET backup_statuts = json('{\"type_devis\":\"' || COALESCE(type_devis, '') || '\",\"statut\":\"' || COALESCE(statut, '') || '\",\"statut_prospect\":\"' || COALESCE(statut_prospect, '') || '\"}')
            ");
        } else {
            // MySQL/PostgreSQL
            DB::update("
                UPDATE devis 
                SET backup_statuts = JSON_OBJECT(
                    'type_devis', type_devis,
                    'statut', statut,
                    'statut_prospect', statut_prospect
                )
            ");
        }

        // Initialiser l'historique des statuts (simplifié pour SQLite)
        if ($driver === 'sqlite') {
            DB::update("
                UPDATE devis 
                SET historique_statuts = json('[{\"ancien_statut\":null,\"nouveau_statut\":\"migration_initiale\",\"date\":\"' || ? || '\",\"motif\":\"Migration vers statut unifié\",\"utilisateur\":\"SYSTEM\"}]')
            ", [$dateNow]);
        } else {
            DB::update("
                UPDATE devis 
                SET historique_statuts = JSON_ARRAY(
                    JSON_OBJECT(
                        'ancien_statut', NULL,
                        'nouveau_statut', 'migration_initiale',
                        'date', NOW(),
                        'motif', 'Migration vers statut unifié',
                        'utilisateur', 'SYSTEM'
                    )
                )
            ");
        }

        // MIGRATION DES PROSPECTS
        // Prospect brouillon
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'prospect_brouillon'
            WHERE type_devis = 'prospect' 
            AND (statut_prospect = 'brouillon' OR statut_prospect IS NULL)
        ");

        // Prospect envoyé
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'prospect_envoye'
            WHERE type_devis = 'prospect' 
            AND statut_prospect = 'envoye'
        ");

        // Prospect en négociation
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'prospect_negocie'
            WHERE type_devis = 'prospect' 
            AND statut_prospect = 'negocie'
        ");

        // Prospect accepté
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'prospect_accepte'
            WHERE type_devis = 'prospect' 
            AND statut_prospect = 'accepte'
        ");

        // MIGRATION DES DEVIS CHANTIERS
        // Devis chantier déjà facturé
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'facture'
            WHERE type_devis = 'chantier' 
            AND facture_id IS NOT NULL
        ");

        // Devis chantier accepté (prêt à facturer)
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'facturable'
            WHERE type_devis = 'chantier' 
            AND statut = 'accepte' 
            AND facture_id IS NULL
        ");

        // Autres devis chantiers (validés)
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'chantier_valide'
            WHERE type_devis = 'chantier' 
            AND statut_unifie IS NULL
        ");

        // MIGRATION DES PROSPECTS CONVERTIS
        DB::update("
            UPDATE devis 
            SET statut_unifie = 'chantier_valide',
                date_conversion_chantier = converted_at
            WHERE type_devis = 'converti'
        ");

        // Vérifier qu'aucun devis n'a été oublié
        $devisNonMigres = DB::scalar("SELECT COUNT(*) FROM devis WHERE statut_unifie IS NULL") ?? 0;
        
        if ($devisNonMigres > 0) {
            // Fallback pour les cas non prévus
            DB::update("
                UPDATE devis 
                SET statut_unifie = 'chantier_valide'
                WHERE statut_unifie IS NULL
            ");
            
            // Log pour investigation
            \Log::warning("Migration devis: {$devisNonMigres} devis ont été migrés avec le statut par défaut 'chantier_valide'");
        }

        // Ajouter une entrée dans l'historique pour la migration (compatible multi-base)
        if ($driver === 'sqlite') {
            $devisToUpdate = DB::select("SELECT id, type_devis, statut, statut_prospect, statut_unifie FROM devis");
            
            foreach ($devisToUpdate as $devis) {
                $oldStatus = $devis->statut_prospect ?: $devis->statut;
                $historyEntry = json_encode([
                    'ancien_statut' => 'migration_initiale',
                    'nouveau_statut' => $devis->statut_unifie,
                    'date' => $dateNow,
                    'motif' => "Migration: {$devis->type_devis} + {$oldStatus}",
                    'utilisateur' => 'SYSTEM'
                ]);
                
                DB::update("
                    UPDATE devis 
                    SET historique_statuts = json('[' || json_extract(historique_statuts, '$[0]') || ',' || ? || ']')
                    WHERE id = ?
                ", [$historyEntry, $devis->id]);
            }
        } else {
            DB::update("
                UPDATE devis 
                SET historique_statuts = JSON_ARRAY_APPEND(
                    historique_statuts, 
                    ',
                    JSON_OBJECT(
                        'ancien_statut', 'migration_initiale',
                        'nouveau_statut', statut_unifie,
                        'date', NOW(),
                        'motif', CONCAT('Migration: ', type_devis, ' + ', COALESCE(statut_prospect, statut)),
                        'utilisateur', 'SYSTEM'
                    )
                )
            ");
        }
    }

    /**
     * Restaurer les données originales lors du rollback (Compatible SQLite/MySQL)
     */
    private function restoreOriginalData(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite : Extraction manuelle du JSON
            $devisToRestore = DB::select("SELECT id, backup_statuts FROM devis WHERE backup_statuts IS NOT NULL");
            
            foreach ($devisToRestore as $devis) {
                $backup = json_decode($devis->backup_statuts, true);
                if ($backup) {
                    DB::update("
                        UPDATE devis 
                        SET 
                            type_devis = ?,
                            statut_old = ?,
                            statut_prospect = ?
                        WHERE id = ?
                    ", [
                        $backup['type_devis'] ?? 'chantier',
                        $backup['statut'] ?? 'brouillon', 
                        $backup['statut_prospect'],
                        $devis->id
                    ]);
                }
            }
        } else {
            // MySQL/PostgreSQL
            DB::update("
                UPDATE devis 
                SET 
                    type_devis = JSON_UNQUOTE(JSON_EXTRACT(backup_statuts, '$.type_devis')),
                    statut_old = JSON_UNQUOTE(JSON_EXTRACT(backup_statuts, '$.statut')),
                    statut_prospect = JSON_UNQUOTE(JSON_EXTRACT(backup_statuts, '$.statut_prospect'))
                WHERE backup_statuts IS NOT NULL
            ");
        }

        // Nettoyer les valeurs NULL dans les colonnes restaurées
        DB::update("
            UPDATE devis 
            SET 
                type_devis = COALESCE(NULLIF(type_devis, 'null'), 'chantier'),
                statut_old = COALESCE(NULLIF(statut_old, 'null'), 'brouillon')
            WHERE type_devis = 'null' OR statut_old = 'null' OR type_devis IS NULL OR statut_old IS NULL
        ");

        // Vérification de cohérence post-restauration
        $incoherents = DB::scalar("
            SELECT COUNT(*) 
            FROM devis 
            WHERE type_devis NOT IN ('prospect', 'chantier', 'converti')
               OR statut_old NOT IN ('brouillon', 'envoye', 'accepte', 'refuse')
        ") ?? 0;

        if ($incoherents > 0) {
            \Log::error("Rollback devis: {$incoherents} devis ont des valeurs incohérentes après restauration");
        }
    }
};