<?php
// database/migrations/2024_01_04_000000_create_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chantier_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom_original');
            $table->string('nom_fichier');
            $table->string('chemin');
            $table->string('type_mime');
            $table->bigInteger('taille');
            $table->text('description')->nullable();
            $table->enum('type', ['image', 'document', 'plan', 'facture', 'autre'])->default('autre');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};