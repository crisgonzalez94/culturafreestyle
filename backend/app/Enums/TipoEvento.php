<?php

namespace App\Enums;

/**
 * Tipos sugeridos, espejo de TIPOS en src/data/disciplinas.ts. El campo es texto libre.
 */
enum TipoEvento: string
{
    case Rolleada = 'rolleada';
    case Salida = 'salida';
    case Encuentro = 'encuentro';
    case Jam = 'jam';
    case Competencia = 'competencia';
    case Clase = 'clase';
    case Festival = 'festival';
    case Exhibicion = 'exhibicion';

    /**
     * Convierte lo elegido en el formulario público ("Salida grupal", "Exhibición") al valor interno.
     */
    public static function desdeTexto(?string $texto): ?string
    {
        if (blank($texto)) {
            return null;
        }

        $normalizado = str($texto)->ascii()->lower()->before(' ')->toString();

        return self::tryFrom($normalizado)?->value ?? $texto;
    }
}
