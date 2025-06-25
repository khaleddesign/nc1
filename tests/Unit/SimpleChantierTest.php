<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Chantier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleChantierTest extends TestCase
{
    use RefreshDatabase;

    public function test_chantier_creation_basic()
    {
        $client = User::factory()->create(['role' => 'client']);
        $commercial = User::factory()->create(['role' => 'commercial']);
        
        $chantier = Chantier::create([
            'titre' => 'Test Simple',
            'description' => 'Description test',
            'client_id' => $client->id,
            'commercial_id' => $commercial->id,
            'statut' => 'en_cours', // Utilisons un statut qui existe
            'date_debut' => '2025-07-01',
            'date_fin_prevue' => '2025-12-31',
            'budget' => 50000
        ]);

        $this->assertDatabaseHas('chantiers', [
            'titre' => 'Test Simple',
            'statut' => 'en_cours'
        ]);
    }
}