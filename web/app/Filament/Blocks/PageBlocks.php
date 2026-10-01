<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Str;

/**
 * Bloques que se pueden combinar en una página «libre» (Páginas legales): el encabezado (título y bajada, uno por
 * página) y el texto enriquecido, tantos como haga falta. Las páginas de las secciones del sitio tienen su propia
 * pantalla y no usan este constructor.
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
                ->label('Encabezado (título y bajada)')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->maxItems(1)
                ->schema([
                    self::bilingual('title', fn (string $name) => TextInput::make($name)->label('Título')->required($name === 'title.es')),
                    self::bilingual('subtitle', fn (string $name) => TextInput::make($name)->label('Bajada')),
                ]),

            Block::make('texto')
                ->label(fn (?array $state): string => self::etiquetaDeTexto($state))
                ->icon('heroicon-o-document-text')
                ->schema([
                    self::bilingual('content', fn (string $name) => RichEditor::make($name)->label('Contenido')->required($name === 'content.es')),
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
