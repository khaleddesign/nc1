<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('chantier_id')->constrained()->onDelete('cascade');
            $table->foreignId('commercial_id')->constrained('users')->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('statut', ['brouillon', 'envoye', 'accepte', 'refuse', 'expire'])->default('brouillon');
            $table->json('client_info');
            $table->date('date_emission');
            $table->date('date_validite');
            $table->timestamp('date_envoi')->nullable();
            $table->timestamp('date_reponse')->nullable();
            $table->decimal('montant_ht', 10, 2)->default(0);
            $table->decimal('montant_tva', 10, 2)->default(0);
            $table->decimal('montant_ttc', 10, 2)->default(0);
            $table->decimal('taux_tva', 5, 2)->default(20.00);
            $table->text('conditions_generales')->nullable();
            $table->integer('delai_realisation')->nullable();
            $table->text('modalites_paiement')->nullable();
            $table->text('signature_client')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->string('signature_ip')->nullable();
            $table->foreignId('facture_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('converted_at')->nullable();
            $table->text('notes_internes')->nullable();
            $table->timestamps();

            $table->index(['statut', 'date_validite']);
            $table->index(['chantier_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};