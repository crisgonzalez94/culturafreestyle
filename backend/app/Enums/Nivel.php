<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Nivel: string implements HasLabel
{
    case Todos = 'todos';
    case Principiante = 'principiante';
    case Intermedio = 'intermedio';
    case Avanzado = 'avanzado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Todos => 'Todos los niveles',
            self::Principiante => 'Principiante',
            self::Intermedio => 'Intermedio',
            self::Avanzado => 'Avanzado',
        };
    }
}
