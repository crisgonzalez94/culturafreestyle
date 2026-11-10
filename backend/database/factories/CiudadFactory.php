<?php

namespace Database\Factories;

use App\Models\Ciudad;
use App\Models\Provincia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ciudad>
 */
class CiudadFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provincia_id' => Provincia::factory(),
            'nombre' => fake()->unique()->city(),
        ];
    }
}
