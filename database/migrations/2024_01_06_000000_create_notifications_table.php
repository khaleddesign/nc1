<?php
// database/migrations/2024_01_06_000000_create_notifications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('chantier_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'nouveau_commentaire', 'mise_a_jour_etape', 'nouveau_document'
            $table->string('titre');
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};