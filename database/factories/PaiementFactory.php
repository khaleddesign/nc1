<?php
namespace Database\Factories;

use App\Models\Paiement;
use App\Models\Facture;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'facture_id' => Facture::factory(),
            'montant' => fake()->numberBetween(100, 5000),
            'date_paiement' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'mode_paiement' => fake()->randomElement(['virement', 'cheque', 'especes', 'carte']),
            'reference' => fake()->optional()->regexify('[A-Z0-9]{8,12}'),
            'commentaire' => fake()->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function virement(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode_paiement' => 'virement',
            'reference' => 'VIR' . fake()->numerify('########'),
        ]);
    }

    public function cheque(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode_paiement' => 'cheque',
            'reference' => 'CHQ' . fake()->numerify('######'),
        ]);
    }

    public function especes(): static
    {
        return $this->state(fn (array $attributes) => [
            'mode_paiement' => 'especes',
            'reference' => null,
        ]);
    }

    public function pourFacture(Facture $facture): static
    {
        return $this->state(fn (array $attributes) => [
            'facture_id' => $facture->id,
        ]);
    }

    public function avecMontant(float $montant): static
    {
        return $this->state(fn (array $attributes) => [
            'montant' => $montant,
        ]);
    }

    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_paiement' => now()->subDays(fake()->numberBetween(1, 7))->format('Y-m-d'),
        ]);
    }
}