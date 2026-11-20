<?php

namespace Database\Factories;

use App\Models\Metier;
use App\Models\Domaine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Metier>
 */
class MetierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->word(),
            'domaine_id' => Domaine::factory(),
        ];
    }
}
