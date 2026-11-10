<?php

namespace Database\Factories;

use App\Enums\Disciplina;
use App\Enums\TipoOrganizador;
use App\Models\Ciudad;
use App\Models\Organizador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organizador>
 */
class OrganizadorFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->company(),
            'tipo' => fake()->randomElement(TipoOrganizador::cases()),
            'disciplinas' => fake()->randomElements(Disciplina::cases(), 2),
            'ciudad_id' => Ciudad::factory(),
            'instagram' => fake()->userName(),
            'whatsapp' => '54911'.fake()->numerify('########'),
            'email' => fake()->safeEmail(),
            'activo' => true,
        ];
    }
}
