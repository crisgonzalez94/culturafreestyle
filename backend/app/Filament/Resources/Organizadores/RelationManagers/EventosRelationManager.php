<?php

namespace App\Filament\Resources\Organizadores\RelationManagers;

use App\Filament\Resources\Eventos\EventoResource;
use App\Models\Evento;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Eventos del organizador, para ver en su ficha. Se editan desde Eventos.
 */
class EventosRelationManager extends RelationManager
{
    protected static string $relationship = 'eventos';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('titulo')
            ->defaultSort('fecha', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with('ciudad'))
            ->recordUrl(fn (Evento $record): string => EventoResource::getUrl('edit', ['record' => $record]))
            ->columns([
                TextColumn::make('titulo')
                    ->searchable(),
                TextColumn::make('fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('ciudad.nombre')
                    ->label('Ciudad'),
                TextColumn::make('estado')
                    ->badge(),
            ]);
    }
}
