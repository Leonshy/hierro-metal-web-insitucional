<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\DiferencialesTabla;
use App\Filament\Secciones\Widgets\SeccionesInicioTabla;
use App\Filament\Tables\MediaLibraryTable;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class InicioPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Inicio';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Inicio';

    protected static function paginaSlug(): string
    {
        return 'inicio';
    }

    protected static function conBoton(): bool
    {
        return true;
    }

    /** La portada siempre se muestra: es la página principal del sitio. */
    protected static function permiteBorrador(): bool
    {
        return false;
    }

    /** La portada siempre se indexa: es la página principal del sitio. */
    protected static function permiteIndexacion(): bool
    {
        return false;
    }

    protected static function urlPublica(): string
    {
        return '/';
    }

    protected function clavesExtraDelHero(): array
    {
        return ['insignia', 'nota', 'media_id'];
    }

    protected function camposDeLaSeccion(): array
    {
        return [
            Section::make('Foto y textos del hero')
                ->description('La foto va de fondo, con una capa oscura para que se lea el texto. Se sirve en WebP y en varios tamaños según el dispositivo.')
                ->schema([
                    MediaPicker::make('media_id')->label('Foto de fondo')->tableConfiguration(MediaLibraryTable::class),
                    TextInput::make('insignia')->label('Etiqueta amarilla sobre el título')->placeholder('Importación y venta · Mayorista y minorista')->maxLength(80),
                    TextInput::make('nota')->label('Nota debajo de los botones')->placeholder('Todas las chapas con certificado de calidad del fabricante')->maxLength(120),
                ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [DiferencialesTabla::class, SeccionesInicioTabla::class];
    }
}
