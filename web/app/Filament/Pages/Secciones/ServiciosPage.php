<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\PasosTabla;
use App\Filament\Secciones\Widgets\ServiciosTabla;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ServiciosPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'Servicios';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Servicios';

    protected static function paginaSlug(): string
    {
        return 'servicios';
    }

    protected static function conBoton(): bool
    {
        return true;
    }

    protected function clavesExtraDelHero(): array
    {
        return ['pasos_titulo', 'pasos_bajada'];
    }

    protected function camposDeLaSeccion(): array
    {
        return [
            Section::make('Bloque «De tu plano a la obra»')
                ->description('El título y la bajada del bloque oscuro de pasos. Los pasos se editan en la tabla de más abajo.')
                ->schema([
                    TextInput::make('pasos_titulo')->label('Título del bloque')->placeholder('De tu plano a la obra')->maxLength(120),
                    Textarea::make('pasos_bajada')->label('Bajada del bloque')->rows(2)->maxLength(300)
                        ->helperText('Si lo dejás vacío se muestra «Un pedido con servicio de taller sigue siempre los mismos N pasos.»'),
                ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [ServiciosTabla::class, PasosTabla::class];
    }
}
