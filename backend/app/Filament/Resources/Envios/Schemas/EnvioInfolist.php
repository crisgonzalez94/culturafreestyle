<?php

namespace App\Filament\Resources\Envios\Schemas;

use App\Models\Envio;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnvioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lo que mandaron')
                    ->description('Tal cual lo escribió quien completó el formulario.')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('evento')
                            ->columnSpanFull(),
                        TextEntry::make('disciplinas')
                            ->badge(),
                        TextEntry::make('tipo')
                            ->placeholder('—'),
                        TextEntry::make('fecha')
                            ->date('d/m/Y'),
                        TextEntry::make('hora')
                            ->placeholder('—'),
                        IconEntry::make('se_repite')
                            ->label('Se repite')
                            ->boolean(),
                        TextEntry::make('lugar'),
                        TextEntry::make('ciudad')
                            ->placeholder('—'),
                        TextEntry::make('organiza')
                            ->placeholder('—'),
                        TextEntry::make('link')
                            ->url(fn (Envio $record): ?string => $record->link, shouldOpenInNewTab: true)
                            ->placeholder('—')
                            ->columnSpanFull(),
                        TextEntry::make('detalles')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
                Section::make('Seguimiento')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('estado')
                            ->badge(),
                        TextEntry::make('created_at')
                            ->label('Llegó')
                            ->since()
                            ->dateTimeTooltip('d/m/Y H:i'),
                        TextEntry::make('eventoCargado.titulo')
                            ->label('Evento cargado')
                            ->placeholder('—'),
                    ]),
            ])
            ->columns(1);
    }
}
