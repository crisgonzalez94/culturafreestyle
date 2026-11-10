<?php

namespace App\Filament\Resources\Envios\Pages;

use App\Filament\Resources\Envios\EnvioResource;
use App\Filament\Resources\Eventos\EventoResource;
use App\Models\Envio;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewEnvio extends ViewRecord
{
    protected static string $resource = EnvioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EnvioResource::accionCrearEvento(),
            Action::make('verEvento')
                ->label('Ver evento cargado')
                ->visible(fn (Envio $record): bool => $record->eventoCargado()->exists())
                ->url(fn (Envio $record): string => EventoResource::getUrl('edit', ['record' => $record->eventoCargado])),
            EnvioResource::accionDescartar(),
            EnvioResource::accionReabrir(),
        ];
    }
}
