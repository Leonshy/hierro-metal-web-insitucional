<?php

namespace App\Filament\Resources\Vendedores;

use App\Filament\Resources\Vendedores\Pages\CreateVendedor;
use App\Filament\Resources\Vendedores\Pages\EditVendedor;
use App\Filament\Resources\Vendedores\Pages\ListVendedores;
use App\Filament\Resources\Vendedores\Schemas\VendedorForm;
use App\Filament\Resources\Vendedores\Tables\VendedoresTable;
use App\Models\Vendedor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendedorResource extends Resource
{
    protected static ?string $model = Vendedor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Vendedores';

    protected static ?string $modelLabel = 'Vendedor';

    protected static ?string $pluralModelLabel = 'Vendedores';

    protected static string|UnitEnum|null $navigationGroup = 'Configuraciones';

    protected static ?int $navigationSort = 18;

    public static function form(Schema $schema): Schema
    {
        return VendedorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendedoresTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendedores::route('/'),
            'create' => CreateVendedor::route('/create'),
            'edit' => EditVendedor::route('/{record}/edit'),
        ];
    }
}
