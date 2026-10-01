<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Blocks\PageBlocks;
use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\CompromisosTabla;
use App\Models\Page;
use App\Services\Html\HtmlSanitizer;
use App\Support\FranjaCalidad;
use BackedEnum;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class CalidadPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Calidad';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Calidad';

    protected static function paginaSlug(): string
    {
        return 'calidad';
    }

    protected function camposDeLaSeccion(): array
    {
        return [
            Section::make('Textos de la política')
                ->description('Cada texto es independiente. El primero es la introducción y va antes de los compromisos; los demás van después, en el orden de esta lista (se arrastran para reordenar).')
                ->schema([
                    Builder::make('textos')
                        ->hiddenLabel()
                        ->blocks([PageBlocks::bloque('texto')])
                        ->addActionLabel('Agregar texto')
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->blockNumbers(false),
                ]),
            $this->camposDeFranja('inicio', 'Franja de calidad en la portada', 'La franja amarilla de la portada (si esa sección está activa en Inicio).'),
            $this->camposDeFranja('servicios', 'Franja de calidad en Servicios', 'La franja amarilla con la que cierra la página de Servicios.'),
        ];
    }

    protected function clavesExtraDelHero(): array
    {
        return FranjaCalidad::claves();
    }

    /** Un grupo de campos (título, bajada, botón) de una de las franjas, con el texto de siempre como ayuda. */
    private function camposDeFranja(string $donde, string $titulo, string $descripcion): Section
    {
        $porDefecto = FranjaCalidad::POR_DEFECTO[$donde];

        return Section::make($titulo)
            ->description($descripcion)
            ->collapsed()
            ->schema([
                TextInput::make("franja_{$donde}_titulo")->label('Título')->placeholder($porDefecto['titulo'])->maxLength(160),
                Textarea::make("franja_{$donde}_bajada")->label('Bajada')->placeholder($porDefecto['bajada'])->rows(2)->maxLength(300),
                TextInput::make("franja_{$donde}_boton")->label('Texto del botón')->placeholder($porDefecto['boton'])->maxLength(60)
                    ->helperText('El botón lleva a la página de política de calidad. Si dejás un campo vacío, se muestra el texto que aparece de ejemplo.'),
            ]);
    }

    protected function estadoExtra(Page $pagina): array
    {
        return [
            'textos' => collect($pagina->blocks ?? [])->where('type', 'texto')->values()->all(),
        ];
    }

    protected function bloquesDeLaSeccion(array $estado, array $bloques): array
    {
        $limpiador = app(HtmlSanitizer::class);

        return collect($estado['textos'] ?? [])
            ->map(fn (array $bloque): array => [
                'type' => 'texto',
                'data' => ['content' => ['es' => $limpiador->clean((string) ($bloque['data']['content']['es'] ?? ''))]],
            ])
            ->values()
            ->all();
    }

    protected function getFooterWidgets(): array
    {
        return [CompromisosTabla::class];
    }
}
