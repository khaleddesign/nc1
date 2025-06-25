<?php

namespace Database\Factories;

use App\Models\Facture;
use App\Models\Chantier;
use App\Models\User;
use App\Models\Devis;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactureFactory extends Factory
{
    protected $model = Facture::class;

    public function definition(): array
    {
        $montantHt = $this->faker->randomFloat(2, 1000, 50000);
        $tauxTva = 20; // TVA standard
        $montantTva = $montantHt * ($tauxTva / 100);
        $montantTtc = $montantHt + $montantTva;
        $montantPaye = $this->faker->randomFloat(2, 0, $montantTtc);
        $montantRestant = $montantTtc - $montantPaye;

        return [
            'numero' => 'FAC-' . date('Y') . '-' . str_pad($this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'chantier_id' => Chantier::factory(),
            'commercial_id' => User::factory()->commercial(),
            'devis_id' => Devis::factory(),
            'titre' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'statut' => $this->faker->randomElement(['emise', 'envoyee', 'payee', 'partiellement_payee', 'en_retard', 'annulee']),
            
            // ✅ CLIENT_INFO OBLIGATOIRE (JSON)
            'client_info' => json_encode([
                'nom' => $this->faker->name(),
                'email' => $this->faker->safeEmail(),
                'telephone' => $this->faker->phoneNumber(),
                'adresse' => $this->faker->address(),
                'siret' => $this->faker->optional()->numerify('##############'),
                'tva_intracommunautaire' => $this->faker->optional()->numerify('FR##############')
            ]),
            
            'date_emission' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'date_echeance' => $this->faker->dateTimeBetween('now', '+2 months'),
            'date_envoi' => $this->faker->optional()->dateTimeBetween('-2 months', 'now'),
            
            'montant_ht' => $montantHt,
            'montant_tva' => $montantTva,
            'montant_ttc' => $montantTtc,
            'montant_paye' => $montantPaye,
            'montant_restant' => $montantRestant,
            'taux_tva' => $tauxTva,
            
            'delai_paiement' => $this->faker->numberBetween(15, 60),
            'conditions_reglement' => $this->faker->optional()->sentence(),
            'reference_commande' => $this->faker->optional()->numerify('CMD-####'),
            
            'date_paiement_complet' => $montantRestant <= 0 ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'nb_relances' => $this->faker->numberBetween(0, 3),
            'derniere_relance' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'notes_internes' => $this->faker->optional()->paragraph(),
        ];
    }

    // États spécifiques
    public function emise(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'emise',
            'date_envoi' => null,
            'montant_paye' => 0,
            'montant_restant' => $attributes['montant_ttc'],
            'date_paiement_complet' => null,
        ]);
    }

    public function envoyee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'envoyee',
            'date_envoi' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'montant_paye' => 0,
            'montant_restant' => $attributes['montant_ttc'],
        ]);
    }

    public function payee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'payee',
            'montant_paye' => $attributes['montant_ttc'],
            'montant_restant' => 0,
            'date_paiement_complet' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function partiellementPayee(): static
    {
        return $this->state(function (array $attributes) {
            $montantPartiel = $this->faker->randomFloat(2, 100, $attributes['montant_ttc'] - 100);
            return [
                'statut' => 'partiellement_payee',
                'montant_paye' => $montantPartiel,
                'montant_restant' => $attributes['montant_ttc'] - $montantPartiel,
                'date_paiement_complet' => null,
            ];
        });
    }

    public function enRetard(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_retard',
            'date_echeance' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
            'nb_relances' => $this->faker->numberBetween(1, 5),
            'derniere_relance' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function avecClientSpecifique(User $client): static
    {
        return $this->state(fn (array $attributes) => [
            'client_info' => json_encode([
                'nom' => $client->name,
                'email' => $client->email,
                'telephone' => $client->telephone ?? $this->faker->phoneNumber(),
                'adresse' => $client->adresse ?? $this->faker->address(),
                'siret' => null,
                'tva_intracommunautaire' => null
            ]),
        ]);
    }
}