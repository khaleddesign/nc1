<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            // Conformité facturation électronique
            $table->json('donnees_structurees')->nullable()->after('notes_internes');
            $table->string('format_electronique')->default('pdf')->after('donnees_structurees');
            $table->string('hash_integrite')->nullable()->after('format_electronique');
            $table->boolean('conforme_loi')->default(false)->after('hash_integrite');
            $table->timestamp('date_transmission')->nullable()->after('conforme_loi');
            $table->string('numero_chronologique')->nullable()->after('date_transmission');
            
            // Index pour les recherches
            $table->index(['conforme_loi', 'date_emission']);
            $table->index('numero_chronologique');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn([
                'donnees_structurees', 'format_electronique', 'hash_integrite',
                'conforme_loi', 'date_transmission', 'numero_chronologique'
            ]);
        });
    }
};