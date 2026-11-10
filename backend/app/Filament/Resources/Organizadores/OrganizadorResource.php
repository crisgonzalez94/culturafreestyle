<?php

namespace App\Filament\Resources\Organizadores;

use App\Filament\Resources\Organizadores\Pages\CreateOrganizador;
use App\Filament\Resources\Organizadores\Pages\EditOrganizador;
use App\Filament\Resources\Organizadores\Pages\ListOrganizadores;
use App\Filament\Resources\Organizadores\Pages\ViewOrganizador;
use App\Filament\Resources\Organizadores\RelationManagers\EventosRelationManager;
use App\Filament\Resources\Organizadores\Schemas\OrganizadorForm;
use App\Filament\Resources\Organizadores\Schemas\OrganizadorInfolist;
use App\Filament\Resources\Organizadores\Tables\OrganizadoresTable;
use App\Models\Organizador;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OrganizadorResource extends Resource
{
    protected static ?string $model = Organizador::class;

    protected static ?string $modelLabel = 'organizador';

    protected static ?string $pluralModelLabel = 'organizadores';

    protected static string|UnitEnum|null $navigationGroup = 'Directorio';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $slug = 'organizadores';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return OrganizadorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrganizadorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizadoresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EventosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizadores::route('/'),
            'create' => CreateOrganizador::route('/create'),
            'view' => ViewOrganizador::route('/{record}'),
            'edit' => EditOrganizador::route('/{record}/edit'),
        ];
    }
}
