<?php

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
            // Rendre chantier_id nullable pour permettre les devis prospects
            $table->foreignId('chantier_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            // Remettre chantier_id non-nullable
            $table->foreignId('chantier_id')->nullable(false)->change();
        });
    }
};