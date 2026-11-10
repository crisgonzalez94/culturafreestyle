<?php

namespace App\Models;

use Database\Factories\ProvinciaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['pais_id', 'nombre'])]
class Provincia extends Model
{
    /** @use HasFactory<ProvinciaFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Pais, $this>
     */
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }

    /**
     * @return HasMany<Ciudad, $this>
     */
    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class);
    }
}
