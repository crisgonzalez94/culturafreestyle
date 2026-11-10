<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EstadoEnvio: string implements HasColor, HasLabel
{
    case Pendiente = 'pendiente';
    case Cargado = 'cargado';
    case Descartado = 'descartado';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::Cargado => 'Cargado como evento',
            self::Descartado => 'Descartado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pendiente => 'warning',
            self::Cargado => 'success',
            self::Descartado => 'gray',
        };
    }
}
