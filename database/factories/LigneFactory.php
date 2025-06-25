<?php

namespace Database\Factories;

use App\Models\Ligne;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Database\Eloquent\Factories\Factory;

class LigneFactory extends Factory
{
    protected $model = Ligne::class;

    public function definition(): array
    {
        $quantite = fake()->numberBetween(1, 20);
        $prixUnitaire = fake()->numberBetween(25, 500);
        $tva = fake()->randomElement([10, 20]);

        return [
            'ligneable_type' => fake()->randomElement([Devis::class, Facture::class]),
            'ligneable_id' => 1, // Sera défini lors de l'utilisation
            'designation' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'quantite' => $quantite,
            'unite' => fake()->randomElement(['u', 'm', 'm²', 'm³', 'h', 'j']),
            'prix_unitaire' => $prixUnitaire,
            'tva' => $tva,
            'ordre' => fake()->numberBetween(1, 10),
        ];
    }

    public function pourDevis(Devis $devis): static
    {
        return $this->state(fn (array $attributes) => [
            'ligneable_type' => Devis::class,
            'ligneable_id' => $devis->id,
        ]);
    }

    public function pourFacture(Facture $facture): static
    {
        return $this->state(fn (array $attributes) => [
            'ligneable_type' => Facture::class,
            'ligneable_id' => $facture->id,
        ]);
    }

    public function maconnerie(): static
    {
        return $this->state(fn (array $attributes) => [
            'designation' => 'Travaux de maçonnerie',
            'unite' => 'm²',
            'prix_unitaire' => fake()->numberBetween(80, 150),
        ]);
    }

    public function plomberie(): static
    {
        return $this->state(fn (array $attributes) => [
            'designation' => 'Installation plomberie',
            'unite' => 'u',
            'prix_unitaire' => fake()->numberBetween(200, 800),
        ]);
    }

    public function electricite(): static
    {
        return $this->state(fn (array $attributes) => [
            'designation' => 'Installation électrique',
            'unite' => 'm',
            'prix_unitaire' => fake()->numberBetween(15, 45),
        ]);
    }

    public function avecPrix(float $prixUnitaire, int $quantite = null): static
    {
        return $this->state(fn (array $attributes) => [
            'prix_unitaire' => $prixUnitaire,
            'quantite' => $quantite ?? $attributes['quantite'],
        ]);
    }
}
