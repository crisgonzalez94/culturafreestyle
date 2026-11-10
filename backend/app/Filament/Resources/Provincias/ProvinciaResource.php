<?php

namespace App\Filament\Resources\Provincias;

use App\Filament\Resources\Provincias\Pages\ManageProvincias;
use App\Models\Pais;
use App\Models\Provincia;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class ProvinciaResource extends Resource
{
    protected static ?string $model = Provincia::class;

    protected static ?string $modelLabel = 'provincia';

    protected static ?string $pluralModelLabel = 'provincias';

    protected static string|UnitEnum|null $navigationGroup = 'Ubicaciones';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $slug = 'provincias';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pais_id')
                    ->label('País')
                    ->relationship('pais', 'nombre')
                    ->default(fn (): ?int => Pais::where('codigo', 'AR')->value('id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(100)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('pais_id', $get('pais_id')),
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->defaultSort('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pais.nombre')
                    ->label('País')
                    ->sortable(),
                TextColumn::make('ciudades_count')
                    ->label('Ciudades')
                    ->counts('ciudades')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('pais')
                    ->label('País')
                    ->relationship('pais', 'nombre'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Provincia $record): bool => $record->ciudades()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProvincias::route('/'),
        ];
    }
}
