<?php

namespace App\Filament\Resources\Ciudades;

use App\Filament\Resources\Ciudades\Pages\ManageCiudades;
use App\Models\Ciudad;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class CiudadResource extends Resource
{
    protected static ?string $model = Ciudad::class;

    protected static ?string $modelLabel = 'ciudad';

    protected static ?string $pluralModelLabel = 'ciudades';

    protected static string|UnitEnum|null $navigationGroup = 'Ubicaciones';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $slug = 'ciudades';

    protected static ?string $recordTitleAttribute = 'nombre';

    /**
     * Campos para crear una ciudad; también se usan para crearla al vuelo desde un evento u organizador.
     *
     * @return array<int, Component|Field>
     */
    public static function campos(): array
    {
        return [
            Select::make('provincia_id')
                ->label('Provincia')
                ->relationship('provincia', 'nombre')
                ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->nombre} ({$record->pais->nombre})")
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('nombre')
                ->required()
                ->maxLength(120)
                ->unique(
                    table: Ciudad::class,
                    ignoreRecord: true,
                    modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('provincia_id', $get('provincia_id')),
                ),
        ];
    }

    /**
     * Selector de ciudad para otros formularios: busca por nombre y permite crear una nueva sin salir.
     */
    public static function selector(string $campo = 'ciudad_id', string $relacion = 'ciudad'): Select
    {
        return Select::make($campo)
            ->label('Ciudad')
            ->relationship($relacion, 'nombre', fn (Builder $query): Builder => $query->with('provincia'))
            ->getOptionLabelFromRecordUsing(fn (Ciudad $record): string => $record->nombre_completo)
            ->searchable()
            ->preload()
            ->createOptionForm(self::campos())
            ->createOptionModalHeading('Nueva ciudad');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(self::campos());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->defaultSort('nombre')
            ->modifyQueryUsing(fn ($query) => $query->with('provincia.pais'))
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('provincia.nombre')
                    ->label('Provincia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('provincia.pais.nombre')
                    ->label('País'),
                TextColumn::make('eventos_count')
                    ->label('Eventos')
                    ->counts('eventos')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('provincia')
                    ->label('Provincia')
                    ->relationship('provincia', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Ciudad $record): bool => $record->eventos()->exists() || $record->organizadores()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCiudades::route('/'),
        ];
    }
}
