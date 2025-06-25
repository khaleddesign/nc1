<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignes', function (Blueprint $table) {
            $table->id();
            $table->morphs('ligneable'); // Pour devis et factures
            $table->integer('ordre')->default(1);
            $table->string('designation');
            $table->text('description')->nullable();
            $table->string('unite', 50)->default('unité');
            $table->decimal('quantite', 10, 3);
            $table->decimal('prix_unitaire_ht', 10, 2);
            $table->decimal('taux_tva', 5, 2)->default(20.00);
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('montant_tva', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
            $table->decimal('remise_pourcentage', 5, 2)->default(0);
            $table->decimal('remise_montant', 10, 2)->default(0);
            $table->string('categorie', 100)->nullable();
            $table->timestamps();

            $table->index(['ligneable_type', 'ligneable_id', 'ordre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes');
    }
};

