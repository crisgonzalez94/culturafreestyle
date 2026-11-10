<?php

namespace App\Models;

use Database\Factories\PaisFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre', 'codigo'])]
#[Table('paises')]
class Pais extends Model
{
    /** @use HasFactory<PaisFactory> */
    use HasFactory;

    /**
     * @return HasMany<Provincia, $this>
     */
    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class);
    }
}
