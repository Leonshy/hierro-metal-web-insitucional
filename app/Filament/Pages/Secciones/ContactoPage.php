<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ContactoPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Contacto';

    protected static ?int $navigationSort = 7;

    protected static ?string $title = 'Contacto';

    protected static function paginaSlug(): string
    {
        return 'contacto';
    }

    /** Contacto siempre se muestra: ahí llegan las cotizaciones y todos los botones «Pedir cotización» llevan a esa página. */
    protected static function permiteBorrador(): bool
    {
        return false;
    }
}
