<?php

namespace App\Filament\Resources\Envios\Tables;

use App\Enums\EstadoEnvio;
use App\Filament\Resources\Envios\EnvioResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnviosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('evento')
                    ->description(fn ($record): string => implode(', ', $record->disciplinas))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('lugar')
                    ->description(fn ($record): ?string => $record->ciudad)
                    ->searchable(['lugar', 'ciudad']),
                TextColumn::make('organiza')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Llegó')
                    ->since()
                    ->dateTimeTooltip('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options(EstadoEnvio::class)
                    ->default(EstadoEnvio::Pendiente->value),
            ])
            ->recordActions([
                ViewAction::make(),
                ActionGroup::make([
                    EnvioResource::accionCrearEvento(),
                    EnvioResource::accionDescartar(),
                    EnvioResource::accionReabrir(),
                ]),
            ]);
    }
}
