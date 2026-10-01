<?php

namespace App\Filament\Widgets;

use App\Models\FormSubmission;
use App\Models\Media;
use App\Models\Page;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Estadísticas del Escritorio: conteos reales de lo que se revisa al entrar.
 * Los módulos de Hierro Metal (cotizaciones, familias) se suman en la Fase 3.
 */
class PanelStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Páginas publicadas', Page::query()->where('status', 'published')->count())
                ->icon('heroicon-o-document-duplicate'),
            Stat::make('Formularios recibidos', FormSubmission::query()->count())
                ->description('Últimos 30 días: '.FormSubmission::query()->where('created_at', '>=', now()->subDays(30))->count())
                ->icon('heroicon-o-inbox'),
            Stat::make('Archivos en la biblioteca', Media::query()->count())
                ->icon('heroicon-o-photo'),
        ];
    }
}
