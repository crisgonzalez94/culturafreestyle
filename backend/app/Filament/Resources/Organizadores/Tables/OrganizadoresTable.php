<?php

namespace App\Filament\Resources\Organizadores\Tables;

use App\Enums\TipoOrganizador;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OrganizadoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('nombre')
            ->modifyQueryUsing(fn ($query) => $query->with('ciudad.provincia'))
            ->columns([
                TextColumn::make('nombre')
                    ->description(fn ($record): ?string => $record->tipo?->getLabel())
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ciudad.nombre_completo')
                    ->label('Ciudad')
                    ->placeholder('—'),
                TextColumn::make('instagram')
                    ->formatStateUsing(fn (string $state): string => "@{$state}")
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('eventos_count')
                    ->label('Eventos')
                    ->counts('eventos')
                    ->sortable(),
                IconColumn::make('activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options(TipoOrganizador::class),
                SelectFilter::make('ciudad')
                    ->relationship('ciudad', 'nombre')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('activo'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
