<?php

namespace Database\Factories;

use App\Enums\Disciplina;
use App\Enums\EstadoEvento;
use App\Enums\Nivel;
use App\Enums\TipoEvento;
use App\Models\Ciudad;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evento>
 */
class EventoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->unique()->sentence(3),
            'disciplinas' => fake()->randomElements(Disciplina::cases(), 1),
            'tipo' => fake()->randomElement(TipoEvento::cases())->value,
            'fecha' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'hora' => fake()->time('H:i'),
            'lugar_nombre' => fake()->streetName(),
            'ciudad_id' => Ciudad::factory(),
            'niveles' => [Nivel::Todos],
            'costo' => 'Gratis',
            'estado' => EstadoEvento::Confirmado,
        ];
    }
}
