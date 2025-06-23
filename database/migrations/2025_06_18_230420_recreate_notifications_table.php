<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ⚠️ Sauvegarder les données uniquement si la table existe déjà
        $notifications = collect();

        if (Schema::hasTable('notifications')) {
            $notifications = DB::table('notifications')->get();
            Schema::drop('notifications');
        }

        // ✅ Créer la nouvelle table notifications avec chantier_id nullable
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('chantier_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('titre');
            $table->text('message');
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });

        // ✅ Restaurer les anciennes données (si existantes)
        if ($notifications->isNotEmpty()) {
            foreach ($notifications as $notification) {
                DB::table('notifications')->insert((array) $notification);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
