<?php

namespace App\Filament\Resources\Familias;

use App\Filament\Resources\Familias\Pages\CreateFamilia;
use App\Filament\Resources\Familias\Pages\EditFamilia;
use App\Filament\Resources\Familias\Pages\ListFamilias;
use App\Filament\Resources\Familias\RelationManagers\LineasRelationManager;
use App\Filament\Resources\Familias\Schemas\FamiliaForm;
use App\Filament\Resources\Familias\Tables\FamiliasTable;
use App\Models\Familia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FamiliaResource extends Resource
{
    protected static ?string $model = Familia::class;

    protected static ?string $slug = 'productos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Productos (familias)';

    protected static ?string $modelLabel = 'Familia';

    protected static ?string $pluralModelLabel = 'Familias de productos';

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return FamiliaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FamiliasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [LineasRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFamilias::route('/'),
            'create' => CreateFamilia::route('/create'),
            'edit' => EditFamilia::route('/{record}/edit'),
        ];
    }
}
