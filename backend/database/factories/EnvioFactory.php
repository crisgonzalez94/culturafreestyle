<?php

namespace Database\Factories;

use App\Models\Envio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Envio>
 */
class EnvioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evento' => fake()->sentence(3),
            'disciplinas' => fake()->randomElements(['Rollers', 'Skate', 'BMX', 'Bici', 'Running'], 2),
            'tipo' => fake()->randomElement(['Rolleada', 'Jam', 'Clase', 'Salida', null]),
            'fecha' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'hora' => fake()->time('H:i'),
            'se_repite' => fake()->boolean(),
            'lugar' => fake()->streetAddress(),
            'ciudad' => fake()->city(),
            'organiza' => fake()->company(),
            'link' => fake()->url(),
            'detalles' => fake()->paragraph(),
        ];
    }
}
