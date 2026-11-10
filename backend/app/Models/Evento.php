<?php

namespace App\Models;

use App\Enums\Disciplina;
use App\Enums\EstadoEvento;
use App\Enums\Frecuencia;
use App\Enums\Nivel;
use App\Models\Concerns\TieneSlug;
use Database\Factories\EventoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Evento publicado en la agenda. Refleja el esquema de src/content.config.ts del sitio.
 */
#[Fillable([
    'titulo',
    'slug',
    'disciplinas',
    'tipo',
    'etiquetas',
    'fecha',
    'fecha_fin',
    'hora',
    'hora_fin',
    'repite_frecuencia',
    'repite_hasta',
    'lugar_nombre',
    'lugar_direccion',
    'ciudad_id',
    'mapa',
    'organizador_id',
    'contacto_instagram',
    'contacto_whatsapp',
    'contacto_web',
    'inscripcion',
    'niveles',
    'costo',
    'estado',
    'destacado',
    'imagen',
    'datos',
    'descripcion',
    'envio_id',
])]
class Evento extends Model
{
    /** @use HasFactory<EventoFactory> */
    use HasFactory;

    use TieneSlug;

    protected function columnaParaSlug(): string
    {
        return 'titulo';
    }

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'disciplinas' => AsEnumCollection::of(Disciplina::class),
            'etiquetas' => 'array',
            'fecha' => 'date',
            'fecha_fin' => 'date',
            'repite_frecuencia' => Frecuencia::class,
            'repite_hasta' => 'date',
            'niveles' => AsEnumCollection::of(Nivel::class),
            'estado' => EstadoEvento::class,
            'destacado' => 'boolean',
            'datos' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Ciudad, $this>
     */
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    /**
     * @return BelongsTo<Organizador, $this>
     */
    public function organizador(): BelongsTo
    {
        return $this->belongsTo(Organizador::class);
    }

    /**
     * Solicitud del formulario público de la que salió este evento, si la hay.
     *
     * @return BelongsTo<Envio, $this>
     */
    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }
}
