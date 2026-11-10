<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EstadoEvento: string implements HasColor, HasLabel
{
    case Confirmado = 'confirmado';
    case AConfirmar = 'a-confirmar';
    case Suspendido = 'suspendido';

    public function getLabel(): string
    {
        return match ($this) {
            self::Confirmado => 'Confirmado',
            self::AConfirmar => 'A confirmar',
            self::Suspendido => 'Suspendido',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Confirmado => 'success',
            self::AConfirmar => 'warning',
            self::Suspendido => 'danger',
        };
    }
}
