<?php
// database/migrations/create_factures_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('chantier_id')->constrained()->onDelete('cascade');
            $table->foreignId('commercial_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('devis_id')->nullable()->constrained()->onDelete('set null');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('statut', ['brouillon', 'envoyee', 'payee', 'annulee'])->default('brouillon');
            $table->json('client_info');
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->timestamp('date_envoi')->nullable();
            $table->decimal('montant_ht', 10, 2)->default(0);
            $table->decimal('montant_tva', 10, 2)->default(0);
            $table->decimal('montant_ttc', 10, 2)->default(0);
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('montant_restant', 10, 2)->default(0);
            $table->decimal('taux_tva', 5, 2)->default(20.00);
            $table->integer('delai_paiement')->default(30);
            $table->text('conditions_reglement')->nullable();
            $table->string('reference_commande')->nullable();
            $table->timestamp('date_paiement_complet')->nullable();
            $table->integer('nb_relances')->default(0);
            $table->timestamp('derniere_relance')->nullable();
            $table->text('notes_internes')->nullable();
            $table->timestamps();

            $table->index(['statut', 'date_echeance']);
            $table->index(['chantier_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};