<?php

namespace App\Filament\Resources\Compromisos;

use App\Filament\Resources\Compromisos\Pages\CreateCompromiso;
use App\Filament\Resources\Compromisos\Pages\EditCompromiso;
use App\Filament\Resources\Compromisos\Pages\ListCompromisos;
use App\Filament\Resources\Compromisos\Schemas\CompromisoForm;
use App\Filament\Resources\Compromisos\Tables\CompromisosTable;
use App\Models\Compromiso;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CompromisoResource extends Resource
{
    protected static ?string $model = Compromiso::class;

    protected static ?string $slug = 'compromisos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Compromisos de calidad';

    protected static ?string $modelLabel = 'Compromiso';

    protected static ?string $pluralModelLabel = 'Compromisos de calidad';

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 14;

    public static function form(Schema $schema): Schema
    {
        return CompromisoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompromisosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompromisos::route('/'),
            'create' => CreateCompromiso::route('/create'),
            'edit' => EditCompromiso::route('/{record}/edit'),
        ];
    }
}
