<?php

namespace App\Filament\Resources\Eventos\Schemas;

use App\Enums\Disciplina;
use App\Enums\EstadoEvento;
use App\Enums\Frecuencia;
use App\Enums\Nivel;
use App\Enums\TipoEvento;
use App\Filament\Resources\Ciudades\CiudadResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EventoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('envio_id'),
                Section::make('Evento')
                    ->columns(2)
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(160)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('URL')
                            ->prefix('/eventos/')
                            ->helperText('Si lo dejás vacío se arma solo con el título.')
                            ->alphaDash()
                            ->maxLength(180)
                            ->unique(ignoreRecord: true),
                        Select::make('disciplinas')
                            ->options(Disciplina::class)
                            ->multiple()
                            ->required(),
                        TextInput::make('tipo')
                            ->required()
                            ->maxLength(60)
                            ->datalist(array_column(TipoEvento::cases(), 'value'))
                            ->helperText('Elegí uno sugerido o escribí otro.'),
                        TagsInput::make('etiquetas')
                            ->placeholder('nocturna, slalom…'),
                        Select::make('estado')
                            ->options(EstadoEvento::class)
                            ->default(EstadoEvento::Confirmado)
                            ->required(),
                        Toggle::make('destacado')
                            ->inline(false),
                    ]),
                Section::make('Cuándo')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('fecha')
                            ->required(),
                        DatePicker::make('fecha_fin')
                            ->label('Fecha de fin')
                            ->helperText('Solo si dura más de un día.')
                            ->afterOrEqual('fecha'),
                        TimePicker::make('hora')
                            ->seconds(false)
                            ->format('H:i'),
                        TimePicker::make('hora_fin')
                            ->label('Hora de fin')
                            ->seconds(false)
                            ->format('H:i'),
                        Select::make('repite_frecuencia')
                            ->label('Se repite')
                            ->options(Frecuencia::class)
                            ->placeholder('No se repite')
                            ->live(),
                        DatePicker::make('repite_hasta')
                            ->label('Se repite hasta')
                            ->visible(fn (Get $get): bool => filled($get('repite_frecuencia'))),
                    ]),
                Section::make('Dónde')
                    ->columns(2)
                    ->schema([
                        TextInput::make('lugar_nombre')
                            ->label('Lugar')
                            ->placeholder('Planetario, skatepark, plaza…')
                            ->required()
                            ->maxLength(200),
                        TextInput::make('lugar_direccion')
                            ->label('Dirección')
                            ->maxLength(200),
                        CiudadResource::selector()
                            ->required(),
                        TextInput::make('mapa')
                            ->label('Link de Google Maps')
                            ->helperText('Opcional: si no, se arma solo con el lugar.')
                            ->url()
                            ->maxLength(500),
                    ]),
                Section::make('Quién organiza')
                    ->columns(2)
                    ->schema([
                        Select::make('organizador_id')
                            ->label('Organizador')
                            ->relationship('organizador', 'nombre')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(160),
                                TextInput::make('instagram')
                                    ->prefix('@')
                                    ->maxLength(60),
                            ])
                            ->createOptionModalHeading('Nuevo organizador')
                            ->helperText('La ficha completa se edita en Directorio → Organizadores.')
                            ->columnSpanFull(),
                        TextInput::make('contacto_instagram')
                            ->label('Instagram de contacto')
                            ->prefix('@')
                            ->maxLength(60),
                        TextInput::make('contacto_whatsapp')
                            ->label('WhatsApp de contacto')
                            ->tel()
                            ->maxLength(30),
                        TextInput::make('contacto_web')
                            ->label('Web de contacto')
                            ->url()
                            ->maxLength(500),
                        TextInput::make('inscripcion')
                            ->label('Link de inscripción')
                            ->url()
                            ->maxLength(500),
                    ]),
                Section::make('Detalles')
                    ->columns(2)
                    ->schema([
                        Select::make('niveles')
                            ->options(Nivel::class)
                            ->multiple()
                            ->default([Nivel::Todos->value]),
                        TextInput::make('costo')
                            ->default('Gratis')
                            ->required()
                            ->maxLength(100),
                        FileUpload::make('imagen')
                            ->label('Flyer')
                            ->image()
                            ->disk('public')
                            ->directory('flyers')
                            ->columnSpanFull(),
                        KeyValue::make('datos')
                            ->label('Datos extra')
                            ->keyLabel('Dato')
                            ->valueLabel('Valor')
                            ->helperText('Ej: Recorrido → 15 km, Equipo → Casco obligatorio')
                            ->columnSpanFull(),
                        MarkdownEditor::make('descripcion')
                            ->label('Descripción')
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns(1);
    }
}
