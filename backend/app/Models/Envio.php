<?php

namespace App\Models;

use App\Enums\Disciplina;
use App\Enums\EstadoEnvio;
use App\Enums\EstadoEvento;
use App\Enums\Nivel;
use App\Enums\TipoEvento;
use Database\Factories\EnvioFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Evento enviado desde "Sumá tu evento", pendiente de revisión.
 */
#[Fillable([
    'evento',
    'disciplinas',
    'tipo',
    'fecha',
    'hora',
    'se_repite',
    'lugar',
    'ciudad',
    'organiza',
    'link',
    'detalles',
    'estado',
])]
class Envio extends Model
{
    /** @use HasFactory<EnvioFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'estado' => 'pendiente',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'disciplinas' => 'array',
            'fecha' => 'date',
            'se_repite' => 'boolean',
            'estado' => EstadoEnvio::class,
        ];
    }

    /**
     * Evento que se cargó a partir de esta solicitud.
     *
     * @return HasOne<Evento, $this>
     */
    public function eventoCargado(): HasOne
    {
        return $this->hasOne(Evento::class);
    }

    /**
     * Precarga para el formulario de evento con lo que se pueda deducir de la solicitud.
     * Ciudad y organizador se asignan solo si ya existen con ese nombre exacto.
     *
     * @return array<string, mixed>
     */
    public function datosParaEvento(): array
    {
        $esInstagram = $this->link && str_contains($this->link, 'instagram.com');

        return [
            'envio_id' => $this->id,
            'titulo' => $this->evento,
            'disciplinas' => collect($this->disciplinas)
                ->map(fn (string $nombre): ?Disciplina => Disciplina::desdeNombre($nombre))
                ->filter()
                ->map(fn (Disciplina $disciplina): string => $disciplina->value)
                ->values()
                ->all(),
            'tipo' => TipoEvento::desdeTexto($this->tipo),
            'fecha' => $this->fecha?->toDateString(),
            'hora' => $this->hora ? substr($this->hora, 0, 5) : null,
            'lugar_nombre' => $this->lugar,
            'ciudad_id' => $this->buscarPorNombre(Ciudad::class, $this->ciudad),
            'organizador_id' => $this->buscarPorNombre(Organizador::class, $this->organiza),
            'contacto_instagram' => $esInstagram ? str($this->link)->after('instagram.com/')->before('/')->before('?')->toString() : null,
            'contacto_web' => $esInstagram ? null : $this->link,
            'descripcion' => $this->detalles,
            'estado' => EstadoEvento::Confirmado->value,
            'niveles' => [Nivel::Todos->value],
            'costo' => 'Gratis',
        ];
    }

    /**
     * @param  class-string<Model>  $modelo
     */
    private function buscarPorNombre(string $modelo, ?string $nombre): ?int
    {
        if (blank($nombre)) {
            return null;
        }

        return $modelo::whereRaw('LOWER(nombre) = ?', [mb_strtolower(trim($nombre))])->value('id');
    }
}
