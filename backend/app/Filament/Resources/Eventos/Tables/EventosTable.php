<?php

namespace App\Filament\Resources\Eventos\Tables;

use App\Enums\Disciplina;
use App\Enums\EstadoEvento;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('fecha')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['ciudad', 'organizador']))
            ->columns([
                TextColumn::make('titulo')
                    ->label('Título')
                    ->description(fn ($record): string => $record->tipo)
                    ->searchable()
                    ->wrap(),
                TextColumn::make('fecha')
                    ->date('d/m/Y')
                    ->description(fn ($record): ?string => $record->repite_frecuencia?->getLabel())
                    ->sortable(),
                TextColumn::make('hora')
                    ->placeholder('—'),
                TextColumn::make('ciudad.nombre')
                    ->label('Ciudad')
                    ->sortable(),
                TextColumn::make('organizador.nombre')
                    ->label('Organiza')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('disciplinas')
                    ->badge(),
                TextColumn::make('estado')
                    ->badge(),
                IconColumn::make('destacado')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('proximos')
                    ->label('Solo próximos y fijos')
                    ->default()
                    ->query(fn (Builder $query): Builder => $query->where(fn (Builder $query) => $query
                        ->where('fecha', '>=', today())
                        ->orWhere('fecha_fin', '>=', today())
                        ->orWhere(fn (Builder $query) => $query
                            ->whereNotNull('repite_frecuencia')
                            ->where(fn (Builder $query) => $query->whereNull('repite_hasta')->orWhere('repite_hasta', '>=', today()))))),
                SelectFilter::make('disciplina')
                    ->options(Disciplina::class)
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'],
                        fn (Builder $query, string $disciplina) => $query->whereJsonContains('disciplinas', $disciplina),
                    )),
                SelectFilter::make('estado')
                    ->options(EstadoEvento::class),
                SelectFilter::make('ciudad')
                    ->relationship('ciudad', 'nombre')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('organizador')
                    ->relationship('organizador', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
