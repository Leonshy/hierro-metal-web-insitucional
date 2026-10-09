<?php

namespace App\Filament\Resources\Novedades;

use App\Filament\Pages\Secciones\NovedadesPage;
use App\Filament\Resources\Novedades\Pages\CreateNovedad;
use App\Filament\Resources\Novedades\Pages\EditNovedad;
use App\Filament\Resources\Novedades\Pages\ListNovedades;
use App\Filament\Resources\Novedades\Schemas\NovedadForm;
use App\Filament\Resources\Novedades\Tables\NovedadesTable;
use App\Filament\Secciones\Concerns\PerteneceAUnaSeccion;
use App\Models\Novedad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NovedadResource extends Resource
{
    use PerteneceAUnaSeccion;

    protected static ?string $model = Novedad::class;

    protected static ?string $slug = 'novedades';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Novedades';

    protected static ?string $modelLabel = 'Novedad';

    protected static ?string $pluralModelLabel = 'Novedades';

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 15;

    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return NovedadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NovedadesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNovedades::route('/'),
            'create' => CreateNovedad::route('/create'),
            'edit' => EditNovedad::route('/{record}/edit'),
        ];
    }

    protected static function seccion(): string
    {
        return NovedadesPage::class;
    }
}
