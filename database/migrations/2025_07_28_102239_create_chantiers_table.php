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
        Schema::create('chantiers', function (Blueprint $table) {
            $table->id();
            
            // Informations principales
            $table->string('titre');
            $table->text('description')->nullable();
            
            // Relations
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commercial_id')->constrained('users')->onDelete('cascade');
            
            // Statut du chantier
            $table->enum('statut', ['planifie', 'en_cours', 'termine'])->default('planifie');
            
            // Dates
            $table->date('date_debut')->nullable();
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_fin_effective')->nullable();
            
            // Informations financières et de suivi
            $table->decimal('budget', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->decimal('avancement_global', 5, 2)->default(0.00);
            
            // États
            $table->boolean('active')->default(true);
            $table->boolean('hidden_for_commercial')->default(false);
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['client_id', 'statut']);
            $table->index(['commercial_id', 'statut']);
            $table->index(['statut', 'date_debut']);
            $table->index('hidden_for_commercial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chantiers');
    }
};