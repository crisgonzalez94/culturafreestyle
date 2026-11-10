<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnvioRequest extends FormRequest
{
    /**
     * El formulario es público: cualquiera puede sumar un evento.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'evento' => ['required', 'string', 'max:160'],
            'disciplinas' => ['required', 'array', 'min:1', 'max:10'],
            'disciplinas.*' => ['string', 'max:40'],
            'tipo' => ['nullable', 'string', 'max:60'],
            'fecha' => ['required', 'date_format:Y-m-d'],
            'hora' => ['nullable', 'date_format:H:i'],
            'se_repite' => ['boolean'],
            'lugar' => ['required', 'string', 'max:200'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'organiza' => ['nullable', 'string', 'max:160'],
            'link' => ['nullable', 'url', 'max:500'],
            'detalles' => ['nullable', 'string', 'max:4000'],
            // Honeypot: campo oculto en el formulario que solo completan los bots.
            'web' => ['prohibited'],
        ];
    }
}
