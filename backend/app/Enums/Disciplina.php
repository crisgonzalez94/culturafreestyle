<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * Espejo de DISCIPLINAS en src/data/disciplinas.ts del sitio.
 */
enum Disciplina: string implements HasLabel
{
    case Rollers = 'rollers';
    case Skate = 'skate';
    case Bmx = 'bmx';
    case Bici = 'bici';
    case Scooter = 'scooter';
    case Running = 'running';
    case Otro = 'otro';

    public function getLabel(): string
    {
        return match ($this) {
            self::Rollers => '🛼 Rollers',
            self::Skate => '🛹 Skate',
            self::Bmx => '🚲 BMX',
            self::Bici => '🚴 Bici',
            self::Scooter => '🛴 Scooter',
            self::Running => '🏃 Running',
            self::Otro => '✨ Otro',
        };
    }

    /**
     * Busca la disciplina por el nombre que muestra el formulario público ("Rollers", "BMX"...).
     */
    public static function desdeNombre(string $nombre): ?self
    {
        return self::tryFrom(mb_strtolower(trim($nombre)));
    }
}
