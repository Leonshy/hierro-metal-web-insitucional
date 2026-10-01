<?php

namespace App\Filament\Resources\Rubros;

use App\Filament\Pages\Secciones\ContactoPage;
use App\Filament\Resources\Rubros\Pages\CreateRubro;
use App\Filament\Resources\Rubros\Pages\EditRubro;
use App\Filament\Resources\Rubros\Pages\ListRubros;
use App\Filament\Resources\Rubros\Schemas\RubroForm;
use App\Filament\Resources\Rubros\Tables\RubrosTable;
use App\Filament\Secciones\Concerns\PerteneceAUnaSeccion;
use App\Models\Rubro;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RubroResource extends Resource
{
    use PerteneceAUnaSeccion;

    protected static ?string $model = Rubro::class;

    protected static ?string $slug = 'rubros';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Rubros del formulario';

    protected static ?string $modelLabel = 'Rubro';

    protected static ?string $pluralModelLabel = 'Rubros del formulario';

    protected static string|UnitEnum|null $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 19;

    public static function form(Schema $schema): Schema
    {
        return RubroForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RubrosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRubros::route('/'),
            'create' => CreateRubro::route('/create'),
            'edit' => EditRubro::route('/{record}/edit'),
        ];
    }

    protected static function seccion(): string
    {
        return ContactoPage::class;
    }
}
