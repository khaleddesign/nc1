<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter le champ pour cacher les chantiers aux commerciaux
     */
    public function up(): void
    {
        Schema::table('chantiers', function (Blueprint $table) {
            // Ajouter une colonne "caché pour commercial" 
            // Par défaut = false (visible)
            $table->boolean('hidden_for_commercial')->default(false)->after('notes');
        });
    }

    /**
     * Supprimer le champ si on veut annuler
     */
    public function down(): void
    {
        Schema::table('chantiers', function (Blueprint $table) {
            $table->dropColumn('hidden_for_commercial');
        });
    }
};