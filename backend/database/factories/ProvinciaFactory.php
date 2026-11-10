<?php

namespace Database\Factories;

use App\Models\Pais;
use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Provincia>
 */
class ProvinciaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pais_id' => Pais::factory(),
            'nombre' => fake()->unique()->state(),
        ];
    }
}
