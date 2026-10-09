<?php

namespace App\Filament\Resources\Popups;

use App\Filament\Resources\Popups\Pages\CreatePopup;
use App\Filament\Resources\Popups\Pages\EditPopup;
use App\Filament\Resources\Popups\Pages\ListPopups;
use App\Filament\Resources\Popups\Schemas\PopupForm;
use App\Filament\Resources\Popups\Tables\PopupsTable;
use App\Models\Popup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PopupResource extends Resource
{
    protected static ?string $model = Popup::class;

    protected static ?string $slug = 'popups';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $navigationLabel = 'Pop-ups';

    protected static ?string $modelLabel = 'Pop-up';

    protected static ?string $pluralModelLabel = 'Pop-ups';

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 9;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return PopupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PopupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPopups::route('/'),
            'create' => CreatePopup::route('/create'),
            'edit' => EditPopup::route('/{record}/edit'),
        ];
    }
}
