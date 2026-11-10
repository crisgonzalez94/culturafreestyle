<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Genera un slug único a partir de otra columna al crear el registro, si no vino uno.
 */
trait TieneSlug
{
    abstract protected function columnaParaSlug(): string;

    protected static function bootTieneSlug(): void
    {
        static::creating(function (self $modelo): void {
            if (blank($modelo->slug)) {
                $modelo->slug = $modelo->slugUnico(Str::slug($modelo->{$modelo->columnaParaSlug()}));
            }
        });
    }

    private function slugUnico(string $base): string
    {
        $slug = $base;
        $sufijo = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$sufijo}";
            $sufijo++;
        }

        return $slug;
    }
}
