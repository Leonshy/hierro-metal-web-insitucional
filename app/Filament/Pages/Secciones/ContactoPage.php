<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\RubrosTabla;
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

    protected function getFooterWidgets(): array
    {
        return [RubrosTabla::class];
    }
}
