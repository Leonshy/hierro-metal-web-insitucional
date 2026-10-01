<?php

namespace App\Filament\Blocks;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Tables\MediaLibraryTable;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Str;

/**
 * Catálogo de bloques del constructor de páginas — implementa los 16 bloques
 * de docs/02-ux-arquitectura-informacion.md §8, cubriendo todo el inventario
 * de contenido real migrado.
 *
 * Los campos de texto de cada bloque quedan guardados como {"es": "..."} (el sitio es sólo en español).
 */
class PageBlocks
{
    /** Un bloque por nombre (por ejemplo `texto`), para usarlo en pantallas que no ofrecen todos los tipos. */
    public static function bloque(string $nombre): Block
    {
        return collect(self::for())->first(fn (Block $bloque): bool => $bloque->getName() === $nombre);
    }

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
                    MediaPicker::make('media_id')->label('Foto de la portada')->tableConfiguration(MediaLibraryTable::class)
                        ->helperText('Se sirve en WebP y en varios tamaños según el dispositivo.'),
                    self::bilingual('cta_label', fn (string $name) => TextInput::make($name)->label('Texto del botón')),
                    TextInput::make('cta_url')->label('Enlace del botón'),
                ]),

            Block::make('texto')
                ->label(fn (?array $state): string => self::etiquetaDeTexto($state))
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

    /**
     * Nombre con el que se ve cada bloque de texto en la lista: su primer subtítulo o, si no tiene, sus primeras
     * palabras. Así, varios textos en una misma página se distinguen sin abrirlos.
     *
     * @param  array<string, mixed>|null  $estado
     */
    private static function etiquetaDeTexto(?array $estado): string
    {
        // El editor enriquecido entrega su contenido como HTML o, mientras se edita, en su formato interno (un arreglo).
        $contenido = $estado['content']['es'] ?? '';
        $html = is_array($contenido) ? RichContentRenderer::make($contenido)->toHtml() : (string) $contenido;

        if (preg_match('#<h[1-6][^>]*>(.*?)</h[1-6]>#is', $html, $titulo) && trim(strip_tags($titulo[1])) !== '') {
            return Str::limit(trim(html_entity_decode(strip_tags($titulo[1]))), 60);
        }

        $texto = trim(html_entity_decode(strip_tags($html)));

        return $texto !== '' ? Str::words($texto, 7) : 'Texto enriquecido';
    }

    /** El sitio es sólo en español: el campo se guarda como {"es": "…"} (formato heredado) sin selector de idioma. */
    private static function bilingual(string $field, \Closure $factory): Component
    {
        return $factory("{$field}.es");
    }
}
