<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Blocks\PageBlocks;
use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\CompromisosTabla;
use App\Models\Page;
use App\Services\Html\HtmlSanitizer;
use BackedEnum;
use Filament\Forms\Components\Builder;
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
        ];
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
