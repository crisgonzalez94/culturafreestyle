<?php

namespace App\Filament\Resources\Eventos\Pages;

use App\Enums\EstadoEnvio;
use App\Filament\Resources\Eventos\EventoResource;
use App\Models\Envio;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\Url;

class CreateEvento extends CreateRecord
{
    protected static string $resource = EventoResource::class;

    /**
     * Solicitud del formulario público desde la que se está cargando el evento (?envio=ID).
     */
    #[Url]
    public ?int $envio = null;

    protected function fillForm(): void
    {
        $envio = $this->envio ? Envio::find($this->envio) : null;

        if (! $envio) {
            parent::fillForm();

            return;
        }

        $this->callHook('beforeFill');
        $this->form->fill($envio->datosParaEvento());
        $this->callHook('afterFill');
    }

    protected function afterCreate(): void
    {
        $this->record->envio?->update(['estado' => EstadoEnvio::Cargado]);
    }
}
