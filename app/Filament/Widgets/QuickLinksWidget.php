<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Cotizaciones\CotizacionResource;
use App\Filament\Resources\Menus\MenuResource;
use App\Filament\Resources\Pages\PageResource;
use Filament\Widgets\Widget;

/**
 * "Accesos directos" pedidos para el Escritorio (Fase 10) — los atajos que
 * más se usan al empezar el día: crear contenido nuevo o revisar lo que
 * llegó (formularios), sin tener que ir a buscarlos en el menú lateral.
 */
class QuickLinksWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-links-widget';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<int, array{label: string, url: string, icon: string}>
     */
    public function getLinks(): array
    {
        return [
            ['label' => 'Nueva página', 'url' => PageResource::getUrl('create'), 'icon' => 'heroicon-o-document-duplicate'],
            ['label' => 'Ver cotizaciones recibidas', 'url' => CotizacionResource::getUrl('index'), 'icon' => 'heroicon-o-inbox'],
            ['label' => 'Administrar menús', 'url' => MenuResource::getUrl('index'), 'icon' => 'heroicon-o-bars-3'],
        ];
    }
}
