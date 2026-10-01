<?php

namespace App\Filament\Resources\Pasos;

use App\Filament\Pages\Secciones\ServiciosPage;
use App\Filament\Resources\Pasos\Pages\CreatePaso;
use App\Filament\Resources\Pasos\Pages\EditPaso;
use App\Filament\Resources\Pasos\Pages\ListPasos;
use App\Filament\Resources\Pasos\Schemas\PasoForm;
use App\Filament\Resources\Pasos\Tables\PasosTable;
use App\Filament\Secciones\Concerns\PerteneceAUnaSeccion;
use App\Models\Paso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PasoResource extends Resource
{
    use PerteneceAUnaSeccion;

    protected static ?string $model = Paso::class;

    protected static ?string $slug = 'pasos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $navigationLabel = 'Pasos «De tu plano a la obra»';

    protected static ?string $modelLabel = 'Paso';

    protected static ?string $pluralModelLabel = 'Pasos «De tu plano a la obra»';

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 13;

    public static function form(Schema $schema): Schema
    {
        return PasoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PasosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPasos::route('/'),
            'create' => CreatePaso::route('/create'),
            'edit' => EditPaso::route('/{record}/edit'),
        ];
    }

    protected static function seccion(): string
    {
        return ServiciosPage::class;
    }
}
