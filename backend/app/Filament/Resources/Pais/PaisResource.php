<?php

namespace App\Filament\Resources\Pais;

use App\Filament\Resources\Pais\Pages\ManagePais;
use App\Models\Pais;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PaisResource extends Resource
{
    protected static ?string $model = Pais::class;

    protected static ?string $modelLabel = 'país';

    protected static ?string $pluralModelLabel = 'países';

    protected static string|UnitEnum|null $navigationGroup = 'Ubicaciones';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAmericas;

    protected static ?string $slug = 'paises';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),
                TextInput::make('codigo')
                    ->label('Código ISO')
                    ->helperText('Dos letras, ej: AR, UY, CL')
                    ->length(2)
                    ->unique(ignoreRecord: true),
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
                TextColumn::make('codigo')
                    ->label('Código'),
                TextColumn::make('provincias_count')
                    ->label('Provincias')
                    ->counts('provincias')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Pais $record): bool => $record->provincias()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePais::route('/'),
        ];
    }
}
