<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;

/**
 * Argentina con sus 24 jurisdicciones y ciudades principales. Se puede correr más de una vez.
 */
class UbicacionesSeeder extends Seeder
{
    /**
     * @var array<string, list<string>>
     */
    private const ARGENTINA = [
        'Ciudad Autónoma de Buenos Aires' => ['CABA'],
        'Buenos Aires' => ['La Plata', 'Mar del Plata', 'Bahía Blanca', 'Quilmes', 'Avellaneda', 'Lanús', 'Lomas de Zamora', 'Morón', 'San Isidro', 'Vicente López', 'Tigre', 'San Martín', 'Pilar', 'Tandil', 'Necochea', 'Pinamar', 'Villa Gesell'],
        'Catamarca' => ['San Fernando del Valle de Catamarca'],
        'Chaco' => ['Resistencia'],
        'Chubut' => ['Rawson', 'Comodoro Rivadavia', 'Puerto Madryn', 'Trelew'],
        'Córdoba' => ['Córdoba', 'Villa Carlos Paz', 'Río Cuarto', 'Villa María'],
        'Corrientes' => ['Corrientes'],
        'Entre Ríos' => ['Paraná', 'Concordia', 'Gualeguaychú'],
        'Formosa' => ['Formosa'],
        'Jujuy' => ['San Salvador de Jujuy'],
        'La Pampa' => ['Santa Rosa'],
        'La Rioja' => ['La Rioja'],
        'Mendoza' => ['Mendoza', 'San Rafael', 'Godoy Cruz'],
        'Misiones' => ['Posadas', 'Puerto Iguazú'],
        'Neuquén' => ['Neuquén', 'San Martín de los Andes'],
        'Río Negro' => ['Viedma', 'San Carlos de Bariloche', 'General Roca', 'Cipolletti'],
        'Salta' => ['Salta'],
        'San Juan' => ['San Juan'],
        'San Luis' => ['San Luis', 'Villa Mercedes'],
        'Santa Cruz' => ['Río Gallegos', 'El Calafate'],
        'Santa Fe' => ['Rosario', 'Santa Fe', 'Rafaela', 'Venado Tuerto'],
        'Santiago del Estero' => ['Santiago del Estero'],
        'Tierra del Fuego' => ['Ushuaia', 'Río Grande'],
        'Tucumán' => ['San Miguel de Tucumán'],
    ];

    public function run(): void
    {
        $argentina = Pais::firstOrCreate(['nombre' => 'Argentina'], ['codigo' => 'AR']);

        foreach (self::ARGENTINA as $nombreProvincia => $ciudades) {
            $provincia = $argentina->provincias()->firstOrCreate(['nombre' => $nombreProvincia]);

            foreach ($ciudades as $nombreCiudad) {
                $provincia->ciudades()->firstOrCreate(['nombre' => $nombreCiudad]);
            }
        }
    }
}
