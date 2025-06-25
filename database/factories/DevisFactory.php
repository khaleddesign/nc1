<?php

namespace Database\Factories;

use App\Models\Devis;
use App\Models\Chantier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DevisFactory extends Factory
{
    protected $model = Devis::class;

    public function definition(): array
    {
        $montantHt = $this->faker->randomFloat(2, 1000, 25000);
        $tauxTva = 20; // TVA standard
        $montantTva = $montantHt * ($tauxTva / 100);
        $montantTtc = $montantHt + $montantTva;

        return [
            'numero' => 'DEV-' . date('Y') . '-' . str_pad($this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'chantier_id' => Chantier::factory(),
            'commercial_id' => User::factory()->commercial(),
            'titre' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'statut' => $this->faker->randomElement(['brouillon', 'envoye', 'accepte', 'refuse', 'expire']),
            
            // ✅ CLIENT_INFO OBLIGATOIRE (JSON)
            'client_info' => json_encode([
                'nom' => $this->faker->name(),
                'email' => $this->faker->safeEmail(),
                'telephone' => $this->faker->phoneNumber(),
                'adresse' => $this->faker->address(),
                'siret' => $this->faker->optional()->numerify('##############'),
                'tva_intracommunautaire' => $this->faker->optional()->numerify('FR##############')
            ]),
            
            'date_emission' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'date_validite' => $this->faker->dateTimeBetween('now', '+3 months'),
            'date_envoi' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'date_reponse' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            
            'montant_ht' => $montantHt,
            'montant_tva' => $montantTva,
            'montant_ttc' => $montantTtc,
            'taux_tva' => $tauxTva,
            
            'conditions_generales' => $this->faker->optional()->paragraph(),
            'delai_realisation' => $this->faker->optional()->numberBetween(1, 12) . ' semaines',
            'modalites_paiement' => $this->faker->optional()->sentence(),
            
            'signature_client' => null,
            'signed_at' => null,
            'signature_ip' => null,
            'facture_id' => null,
            'converted_at' => null,
            'notes_internes' => $this->faker->optional()->paragraph(),
        ];
    }

    // États spécifiques
    public function brouillon(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'brouillon',
            'date_envoi' => null,
            'date_reponse' => null,
            'signature_client' => null,
            'signed_at' => null,
        ]);
    }

    public function envoye(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'envoye',
            'date_envoi' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'date_reponse' => null,
            'signature_client' => null,
            'signed_at' => null,
        ]);
    }

    public function accepte(): static
    {
        $dateReponse = $this->faker->dateTimeBetween('-1 month', 'now');
        return $this->state(fn (array $attributes) => [
            'statut' => 'accepte',
            'date_envoi' => $this->faker->dateTimeBetween('-2 months', $dateReponse),
            'date_reponse' => $dateReponse,
            'signature_client' => 'data:image/svg+xml;base64,' . base64_encode('<svg>signature</svg>'),
            'signed_at' => $dateReponse,
            'signature_ip' => $this->faker->ipv4(),
        ]);
    }

    public function refuse(): static
    {
        $dateReponse = $this->faker->dateTimeBetween('-1 month', 'now');
        return $this->state(fn (array $attributes) => [
            'statut' => 'refuse',
            'date_envoi' => $this->faker->dateTimeBetween('-2 months', $dateReponse),
            'date_reponse' => $dateReponse,
            'signature_client' => null,
            'signed_at' => null,
        ]);
    }

    public function expire(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'expire',
            'date_validite' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
            'date_envoi' => $this->faker->dateTimeBetween('-3 months', '-2 months'),
        ]);
    }

    public function avecFacture(): static
    {
        return $this->state(fn (array $attributes) => [
            'facture_id' => \App\Models\Facture::factory(),
            'converted_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
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