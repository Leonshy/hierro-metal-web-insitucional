<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class UbicacionPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $navigationLabel = 'Ubicación';

    protected static ?int $navigationSort = 7;

    protected static ?string $title = 'Ubicación';

    protected static function paginaSlug(): string
    {
        return 'ubicacion';
    }
}
