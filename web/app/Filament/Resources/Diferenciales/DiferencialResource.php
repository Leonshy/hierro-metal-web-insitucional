<?php

namespace App\Filament\Resources\Diferenciales;

use App\Filament\Pages\Secciones\InicioPage;
use App\Filament\Resources\Diferenciales\Pages\CreateDiferencial;
use App\Filament\Resources\Diferenciales\Pages\EditDiferencial;
use App\Filament\Resources\Diferenciales\Pages\ListDiferenciales;
use App\Filament\Resources\Diferenciales\Schemas\DiferencialForm;
use App\Filament\Resources\Diferenciales\Tables\DiferencialesTable;
use App\Filament\Secciones\Concerns\PerteneceAUnaSeccion;
use App\Models\Diferencial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DiferencialResource extends Resource
{
    use PerteneceAUnaSeccion;

    protected static ?string $model = Diferencial::class;

    protected static ?string $slug = 'diferenciales';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Diferenciales (franja amarilla)';

    protected static ?string $modelLabel = 'Diferencial';

    protected static ?string $pluralModelLabel = 'Diferenciales (franja amarilla)';

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 15;

    public static function form(Schema $schema): Schema
    {
        return DiferencialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiferencialesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiferenciales::route('/'),
            'create' => CreateDiferencial::route('/create'),
            'edit' => EditDiferencial::route('/{record}/edit'),
        ];
    }

    protected static function seccion(): string
    {
        return InicioPage::class;
    }
}
