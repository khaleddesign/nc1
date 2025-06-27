<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter uniquement les colonnes manquantes
            $table->unsignedBigInteger('devis_id')->nullable()->after('chantier_id');
            $table->unsignedBigInteger('facture_id')->nullable()->after('devis_id');
            
            // Ajouter les contraintes de clés étrangères
            $table->foreign('devis_id')->references('id')->on('devis')->onDelete('cascade');
            $table->foreign('facture_id')->references('id')->on('factures')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['devis_id']);
            $table->dropForeign(['facture_id']);
            $table->dropColumn(['devis_id', 'facture_id']);
        });
    }
};