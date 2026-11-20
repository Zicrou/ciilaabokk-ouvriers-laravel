<?php

namespace Database\Factories;

use App\Models\Region;
use App\Models\Countries;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Region>
 */
class RegionFactory extends Factory
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
            'country_id' => Countries::factory(),
        ];
    }
}
