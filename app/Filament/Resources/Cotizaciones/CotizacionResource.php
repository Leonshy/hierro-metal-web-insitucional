<?php

namespace App\Filament\Resources\Cotizaciones;

use App\Filament\Resources\Cotizaciones\Pages\EditCotizacion;
use App\Filament\Resources\Cotizaciones\Pages\ListCotizaciones;
use App\Filament\Resources\Cotizaciones\Schemas\CotizacionForm;
use App\Filament\Resources\Cotizaciones\Tables\CotizacionesTable;
use App\Models\Cotizacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CotizacionResource extends Resource
{
    protected static ?string $model = Cotizacion::class;

    protected static ?string $slug = 'cotizaciones';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?string $navigationLabel = 'Cotizaciones';

    protected static ?string $modelLabel = 'Cotización';

    protected static ?string $pluralModelLabel = 'Cotizaciones';

    protected static string|UnitEnum|null $navigationGroup = 'General';

    protected static ?int $navigationSort = 2;

    /** Las cotizaciones las crea el formulario del sitio, nunca el panel. */
    public static function canCreate(): bool
    {
        return false;
    }

    /** Contador de pedidos nuevos en el menú del panel. */
    public static function getNavigationBadge(): ?string
    {
        $nuevas = Cotizacion::query()->where('estado', 'nueva')->count();

        return $nuevas > 0 ? (string) $nuevas : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return CotizacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CotizacionesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCotizaciones::route('/'),
            'edit' => EditCotizacion::route('/{record}/edit'),
        ];
    }
}
