<?php

namespace App\Filament\Resources\Envios;

use App\Enums\EstadoEnvio;
use App\Filament\Resources\Envios\Pages\ListEnvios;
use App\Filament\Resources\Envios\Pages\ViewEnvio;
use App\Filament\Resources\Envios\Schemas\EnvioInfolist;
use App\Filament\Resources\Envios\Tables\EnviosTable;
use App\Filament\Resources\Eventos\EventoResource;
use App\Models\Envio;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Solicitudes que llegan desde "Sumá tu evento". Se leen tal cual y se cargan a mano como eventos.
 */
class EnvioResource extends Resource
{
    protected static ?string $model = Envio::class;

    protected static ?string $modelLabel = 'solicitud';

    protected static ?string $pluralModelLabel = 'solicitudes';

    protected static string|UnitEnum|null $navigationGroup = 'Agenda';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $slug = 'solicitudes';

    protected static ?string $recordTitleAttribute = 'evento';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pendientes = Envio::where('estado', EstadoEnvio::Pendiente)->count();

        return $pendientes > 0 ? (string) $pendientes : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function infolist(Schema $schema): Schema
    {
        return EnvioInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnviosTable::configure($table);
    }

    /**
     * Abre el formulario de nuevo evento precargado con los datos de la solicitud.
     */
    public static function accionCrearEvento(): Action
    {
        return Action::make('crearEvento')
            ->label('Crear evento')
            ->icon(Heroicon::OutlinedCalendarDays)
            ->visible(fn (Envio $record): bool => $record->estado === EstadoEnvio::Pendiente)
            ->url(fn (Envio $record): string => EventoResource::getUrl('create', ['envio' => $record->id]));
    }

    public static function accionDescartar(): Action
    {
        return Action::make('descartar')
            ->label('Descartar')
            ->icon(Heroicon::OutlinedArchiveBoxXMark)
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription('La solicitud queda guardada, pero sale de la lista de pendientes.')
            ->visible(fn (Envio $record): bool => $record->estado === EstadoEnvio::Pendiente)
            ->action(fn (Envio $record) => $record->update(['estado' => EstadoEnvio::Descartado]));
    }

    public static function accionReabrir(): Action
    {
        return Action::make('reabrir')
            ->label('Volver a pendiente')
            ->icon(Heroicon::OutlinedArrowUturnLeft)
            ->color('gray')
            ->visible(fn (Envio $record): bool => $record->estado === EstadoEnvio::Descartado)
            ->action(fn (Envio $record) => $record->update(['estado' => EstadoEnvio::Pendiente]));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEnvios::route('/'),
            'view' => ViewEnvio::route('/{record}'),
        ];
    }
}
