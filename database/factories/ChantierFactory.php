<?php
namespace Database\Factories;

use App\Models\Chantier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChantierFactory extends Factory
{
    protected $model = Chantier::class;

    public function definition(): array
    {
        $dateDebut = fake()->dateTimeBetween('now', '+1 month');
        $dateFinPrevue = fake()->dateTimeBetween($dateDebut, '+6 months');

        return [
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(2),
            'client_id' => User::factory()->client(),
            'commercial_id' => User::factory()->commercial(),
            'statut' => fake()->randomElement(['planifie', 'en_cours', 'termine']),
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin_prevue' => $dateFinPrevue->format('Y-m-d'),
            'date_fin_effective' => null,
            'budget' => fake()->numberBetween(10000, 500000),
            'avancement_global' => fake()->numberBetween(0, 100),
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    public function enCours(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_cours',
            'avancement_global' => fake()->numberBetween(10, 80),
        ]);
    }

    public function planifie(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'planifie',
            'avancement_global' => 0,
        ]);
    }

    public function termine(): static
    {
        return $this->state(function (array $attributes) {
            $dateFinEffective = fake()->dateTimeBetween($attributes['date_debut'], 'now');
            return [
                'statut' => 'termine',
                'avancement_global' => 100,
                'date_fin_effective' => $dateFinEffective->format('Y-m-d'),
            ];
        });
    }
}