<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TipoOrganizador: string implements HasLabel
{
    case Grupo = 'grupo';
    case Club = 'club';
    case Escuela = 'escuela';
    case Marca = 'marca';
    case Persona = 'persona';
    case Organismo = 'organismo';

    public function getLabel(): string
    {
        return match ($this) {
            self::Grupo => 'Grupo / crew',
            self::Club => 'Club o asociación',
            self::Escuela => 'Escuela',
            self::Marca => 'Marca o comercio',
            self::Persona => 'Persona',
            self::Organismo => 'Organismo público',
        };
    }
}
