<?php
// database/migrations/2024_01_03_000000_create_etapes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chantier_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);
            $table->decimal('pourcentage', 5, 2)->default(0);
            $table->date('date_debut')->nullable();
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_fin_effective')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('terminee')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etapes');
    }
};