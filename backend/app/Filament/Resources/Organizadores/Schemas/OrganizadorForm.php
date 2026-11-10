<?php

namespace App\Filament\Resources\Organizadores\Schemas;

use App\Enums\Disciplina;
use App\Enums\TipoOrganizador;
use App\Filament\Resources\Ciudades\CiudadResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizadorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(160),
                        Select::make('tipo')
                            ->options(TipoOrganizador::class),
                        Select::make('disciplinas')
                            ->options(Disciplina::class)
                            ->multiple(),
                        CiudadResource::selector(),
                        Toggle::make('activo')
                            ->helperText('Desactivalo si el grupo ya no organiza eventos.')
                            ->default(true),
                    ]),
                Section::make('Contacto')
                    ->columns(2)
                    ->schema([
                        TextInput::make('instagram')
                            ->prefix('@')
                            ->maxLength(60)
                            ->dehydrateStateUsing(fn (?string $state): ?string => $state ? ltrim(trim($state), '@') : null),
                        TextInput::make('whatsapp')
                            ->tel()
                            ->helperText('Con código de país, sin + ni espacios. Ej: 5491112345678')
                            ->maxLength(30),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('web')
                            ->url()
                            ->maxLength(500),
                    ]),
                Section::make('Sobre el organizador')
                    ->schema([
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(4),
                        Textarea::make('notas')
                            ->label('Notas internas')
                            ->helperText('Solo se ven en el admin.')
                            ->rows(3),
                    ]),
            ])
            ->columns(1);
    }
}
