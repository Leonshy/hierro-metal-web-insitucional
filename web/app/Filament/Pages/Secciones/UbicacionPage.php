<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\HorariosTabla;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class UbicacionPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $navigationLabel = 'Ubicación';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Ubicación';

    protected static function paginaSlug(): string
    {
        return 'ubicacion';
    }

    protected function getFooterWidgets(): array
    {
        return [HorariosTabla::class];
    }
}
