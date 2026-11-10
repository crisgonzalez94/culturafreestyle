<?php

namespace App\Http\Resources;

use App\Models\Envio;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Envio
 */
class EnvioResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'evento' => $this->evento,
            'disciplinas' => $this->disciplinas,
            'tipo' => $this->tipo,
            'fecha' => $this->fecha->toDateString(),
            'hora' => $this->hora,
            'se_repite' => $this->se_repite,
            'lugar' => $this->lugar,
            'ciudad' => $this->ciudad,
            'organiza' => $this->organiza,
            'link' => $this->link,
            'detalles' => $this->detalles,
            'estado' => $this->estado,
            'creado_en' => $this->created_at,
        ];
    }
}
