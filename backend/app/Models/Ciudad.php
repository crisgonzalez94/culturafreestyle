<?php

namespace App\Models;

use Database\Factories\CiudadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['provincia_id', 'nombre'])]
#[Table('ciudades')]
class Ciudad extends Model
{
    /** @use HasFactory<CiudadFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Provincia, $this>
     */
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * @return HasMany<Evento, $this>
     */
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }

    /**
     * @return HasMany<Organizador, $this>
     */
    public function organizadores(): HasMany
    {
        return $this->hasMany(Organizador::class);
    }

    /**
     * "Rosario, Santa Fe" — para mostrar en selectores y listados.
     *
     * @return Attribute<string, never>
     */
    protected function nombreCompleto(): Attribute
    {
        return Attribute::get(fn (): string => $this->provincia
            ? "{$this->nombre}, {$this->provincia->nombre}"
            : $this->nombre);
    }
}
