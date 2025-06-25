<?php

namespace Tests\Feature\Traits;

use App\Models\User;
use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Ligne;

trait WithTestData
{
    protected User $admin;
    protected User $commercial;
    protected User $client;
    protected User $autreClient;
    protected Chantier $chantier;

    protected function setUpTestData(): void
    {
        $this->admin = User::factory()->admin()->create();
        $this->commercial = User::factory()->commercial()->create();
        $this->client = User::factory()->client()->create();
        $this->autreClient = User::factory()->client()->create();
        
        $this->chantier = Chantier::factory()->create([
            'client_id' => $this->client->id,
            'commercial_id' => $this->commercial->id
        ]);
    }

    protected function createDevisWithLignes(array $devisData = [], array $lignesData = []): Devis
    {
        $devis = Devis::factory()->create(array_merge([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id
        ], $devisData));

        if (empty($lignesData)) {
            $lignesData = [
                ['designation' => 'Maçonnerie', 'quantite' => 10, 'prix_unitaire' => 50],
                ['designation' => 'Plomberie', 'quantite' => 5, 'prix_unitaire' => 100]
            ];
        }

        foreach ($lignesData as $ligneData) {
            Ligne::factory()->pourDevis($devis)->create($ligneData);
        }

        return $devis;
    }

    protected function createFactureWithPaiements(array $factureData = [], array $paiementsData = []): Facture
    {
        $facture = Facture::factory()->create(array_merge([
            'chantier_id' => $this->chantier->id,
            'commercial_id' => $this->commercial->id
        ], $factureData));

        foreach ($paiementsData as $paiementData) {
            \App\Models\Paiement::factory()->pourFacture($facture)->create($paiementData);
        }

        return $facture;
    }
}