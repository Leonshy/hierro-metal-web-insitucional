<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\NovedadesTabla;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class NovedadesPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Novedades';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Novedades';

    protected static function paginaSlug(): string
    {
        return 'novedades';
    }

    protected function camposDeLaSeccion(): array
    {
        return [
            Section::make('Cuándo se ve esta sección')
                ->description('Novedades aparece en el menú, en el pie, en la portada y en el mapa del sitio sólo mientras haya al menos una novedad publicada (activa y con su fecha de publicación cumplida). Si no queda ninguna, la sección desaparece sola y vuelve cuando publiques otra.')
                ->schema([]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [NovedadesTabla::class];
    }
}
