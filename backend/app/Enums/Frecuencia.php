<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Frecuencia: string implements HasLabel
{
    case Semanal = 'semanal';
    case Quincenal = 'quincenal';
    case Mensual = 'mensual';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }
}
