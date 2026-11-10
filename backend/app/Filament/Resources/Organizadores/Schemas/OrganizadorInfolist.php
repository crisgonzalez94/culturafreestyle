<?php

namespace App\Filament\Resources\Organizadores\Schemas;

use App\Models\Organizador;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizadorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ficha')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nombre'),
                        TextEntry::make('tipo')
                            ->placeholder('—'),
                        TextEntry::make('ciudad.nombre_completo')
                            ->label('Ciudad')
                            ->placeholder('—'),
                        TextEntry::make('disciplinas')
                            ->badge()
                            ->placeholder('—'),
                        IconEntry::make('activo')
                            ->boolean(),
                    ]),
                Section::make('Contacto')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('instagram')
                            ->formatStateUsing(fn (string $state): string => "@{$state}")
                            ->url(fn (Organizador $record): string => "https://instagram.com/{$record->instagram}", shouldOpenInNewTab: true)
                            ->placeholder('—'),
                        TextEntry::make('whatsapp')
                            ->url(fn (Organizador $record): string => "https://wa.me/{$record->whatsapp}", shouldOpenInNewTab: true)
                            ->placeholder('—'),
                        TextEntry::make('email')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('web')
                            ->url(fn (Organizador $record): ?string => $record->web, shouldOpenInNewTab: true)
                            ->placeholder('—'),
                    ]),
                Section::make('Sobre el organizador')
                    ->schema([
                        TextEntry::make('descripcion')
                            ->label('Descripción')
                            ->placeholder('—'),
                        TextEntry::make('notas')
                            ->label('Notas internas')
                            ->placeholder('—'),
                    ]),
            ])
            ->columns(1);
    }
}
