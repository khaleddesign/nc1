<?php
// database/migrations/2025_07_04_150000_add_flux_support_to_devis_table.php

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
            // Type de devis pour distinguer les flux
            $table->enum('type_devis', ['prospect', 'chantier', 'converti'])
                  ->default('chantier')
                  ->after('chantier_id');
            
            // ID du chantier créé lors de la conversion (pour traçabilité)
            $table->unsignedBigInteger('chantier_converti_id')
                  ->nullable()
                  ->after('type_devis');
            
            // Date de conversion prospect → chantier
            $table->timestamp('date_conversion')
                  ->nullable()
                  ->after('chantier_converti_id');
            
            // Statut enrichi pour les prospects
            $table->enum('statut_prospect', ['brouillon', 'envoye', 'negocie', 'accepte', 'refuse', 'expire', 'converti'])
                  ->nullable()
                  ->after('date_conversion');
            
            // Informations de négociation pour prospects
            $table->json('historique_negociation')
                  ->nullable()
                  ->after('statut_prospect')
                  ->comment('Historique des versions et modifications du devis prospect');
            
            // Référence externe (optionnel pour prospect)
            $table->string('reference_externe')
                  ->nullable()
                  ->after('historique_negociation');
            
            // Index pour optimiser les requêtes
            $table->index(['type_devis', 'statut']);
            $table->index(['type_devis', 'commercial_id']);
            $table->index('chantier_converti_id');
        });
        
        // Clé étrangère pour le chantier converti
        Schema::table('devis', function (Blueprint $table) {
            $table->foreign('chantier_converti_id')
                  ->references('id')
                  ->on('chantiers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropForeign(['chantier_converti_id']);
            $table->dropIndex(['type_devis', 'statut']);
            $table->dropIndex(['type_devis', 'commercial_id']);
            $table->dropIndex(['chantier_converti_id']);
            
            $table->dropColumn([
                'type_devis',
                'chantier_converti_id', 
                'date_conversion',
                'statut_prospect',
                'historique_negociation',
                'reference_externe'
            ]);
        });
    }
};