<?php

namespace App\Filament\Blocks;

use App\Models\SiteSetting;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Catálogo de bloques del constructor de páginas — implementa los 16 bloques
 * de docs/02-ux-arquitectura-informacion.md §8, cubriendo todo el inventario
 * de contenido real migrado.
 *
 * Cada bloque envuelve sus campos de texto en pestañas Español/Italiano —
 * el italiano solo se muestra si está habilitado en la configuración global
 * (ADR-002). Los campos quedan guardados como {"es": "...", "it": "..."}.
 */
class PageBlocks
{
    /** @return array<int, Block> */
    public static function for(): array
    {
        return [
            Block::make('hero')
                ->label('Hero (portada de la página)')
                ->icon('heroicon-o-photo')
                ->schema([
                    self::bilingual('title', fn (string $name) => TextInput::make($name)->label('Título')->required($name === 'title.es')),
                    self::bilingual('subtitle', fn (string $name) => TextInput::make($name)->label('Bajada')),
                    FileUpload::make('image')->label('Imagen de fondo')->image()->directory('bloques'),
                    self::bilingual('cta_label', fn (string $name) => TextInput::make($name)->label('Texto del botón')),
                    TextInput::make('cta_url')->label('Enlace del botón'),
                ]),

            Block::make('texto')
                ->label('Texto enriquecido')
                ->icon('heroicon-o-document-text')
                ->schema([
                    self::bilingual('content', fn (string $name) => RichEditor::make($name)->label('Contenido')->required($name === 'content.es')),
                ]),

            Block::make('imagen_texto')
                ->label('Imagen + texto')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('image')->label('Imagen')->image()->directory('bloques')->required(),
                    self::bilingual('title', fn (string $name) => TextInput::make($name)->label('Título')),
                    self::bilingual('text', fn (string $name) => RichEditor::make($name)->label('Texto')),
                ]),

            Block::make('tarjetas')
                ->label('Tarjetas')
                ->icon('heroicon-o-squares-2x2')
                ->schema([
                    Repeater::make('items')
                        ->label('Tarjetas')
                        ->schema([
                            self::bilingual('title', fn (string $name) => TextInput::make($name)->label('Título')->required($name === 'title.es')),
                            self::bilingual('text', fn (string $name) => TextInput::make($name)->label('Texto')),
                            TextInput::make('url')->label('Enlace (opcional)'),
                        ])
                        ->columns(1),
                ]),

            Block::make('cta')
                ->label('Llamado a la acción destacado')
                ->icon('heroicon-o-megaphone')
                ->schema([
                    self::bilingual('title', fn (string $name) => TextInput::make($name)->label('Título')->required($name === 'title.es')),
                    self::bilingual('text', fn (string $name) => TextInput::make($name)->label('Texto')),
                    self::bilingual('button_label', fn (string $name) => TextInput::make($name)->label('Texto del botón')),
                    TextInput::make('button_url')->label('Enlace del botón'),
                ]),

            Block::make('cifras')
                ->label('Cifras / hitos institucionales')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    Repeater::make('items')
                        ->label('Cifras')
                        ->schema([
                            TextInput::make('number')->label('Número o año')->required(),
                            self::bilingual('label', fn (string $name) => TextInput::make($name)->label('Etiqueta')->required($name === 'label.es')),
                        ])
                        ->columns(2),
                ]),

            Block::make('faq')
                ->label('Acordeón / Preguntas frecuentes')
                ->icon('heroicon-o-question-mark-circle')
                ->schema([
                    Repeater::make('items')
                        ->label('Preguntas')
                        ->schema([
                            self::bilingual('question', fn (string $name) => TextInput::make($name)->label('Pregunta')->required($name === 'question.es')),
                            self::bilingual('answer', fn (string $name) => RichEditor::make($name)->label('Respuesta')->required($name === 'answer.es')),
                        ])
                        ->columns(1),
                ]),

            Block::make('video')
                ->label('Video')
                ->icon('heroicon-o-play-circle')
                ->schema([
                    TextInput::make('url')->label('Enlace del video (YouTube / Vimeo)')->url(),
                    FileUpload::make('file')->label('O archivo de video')->directory('bloques')->acceptedFileTypes(['video/mp4']),
                    FileUpload::make('cover')->label('Imagen de portada (opcional)')->image()->directory('bloques'),
                ]),

            Block::make('mapa')
                ->label('Mapa')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    TextInput::make('address')->label('Dirección')->required(),
                    TextInput::make('latitude')->label('Latitud')->numeric(),
                    TextInput::make('longitude')->label('Longitud')->numeric(),
                ]),
        ];
    }

    private static function bilingual(string $field, \Closure $factory): Tabs
    {
        return Tabs::make($field)
            ->contained(false)
            ->tabs([
                Tab::make('Español')->schema([$factory("{$field}.es")]),
                Tab::make('Italiano')->schema([$factory("{$field}.it")])->visible(fn () => SiteSetting::italianEnabled()),
            ]);
    }
}
