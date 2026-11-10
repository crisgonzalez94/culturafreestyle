<?php

namespace App\Models;

use App\Enums\Disciplina;
use App\Enums\TipoOrganizador;
use App\Models\Concerns\TieneSlug;
use Database\Factories\OrganizadorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Grupo, club, escuela o persona que organiza eventos. Su "ficha" en el admin.
 */
#[Fillable([
    'nombre',
    'slug',
    'tipo',
    'disciplinas',
    'ciudad_id',
    'instagram',
    'whatsapp',
    'email',
    'web',
    'descripcion',
    'notas',
    'activo',
])]
#[Table('organizadores')]
class Organizador extends Model
{
    /** @use HasFactory<OrganizadorFactory> */
    use HasFactory;

    use TieneSlug;

    protected function columnaParaSlug(): string
    {
        return 'nombre';
    }

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'tipo' => TipoOrganizador::class,
            'disciplinas' => AsEnumCollection::of(Disciplina::class),
            'activo' => 'boolean',
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
     * @return HasMany<Evento, $this>
     */
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
