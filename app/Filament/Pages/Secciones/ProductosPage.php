<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\FamiliasTabla;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ProductosPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Productos';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Productos';

    protected static function paginaSlug(): string
    {
        return 'productos';
    }

    protected static function conBoton(): bool
    {
        return true;
    }

    protected function getFooterWidgets(): array
    {
        return [FamiliasTabla::class];
    }
}
