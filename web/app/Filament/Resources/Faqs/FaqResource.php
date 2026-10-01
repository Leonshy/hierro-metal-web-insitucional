<?php

namespace App\Filament\Resources\Faqs;

use App\Filament\Pages\Secciones\PreguntasFrecuentesPage;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Filament\Resources\Faqs\Schemas\FaqForm;
use App\Filament\Resources\Faqs\Tables\FaqsTable;
use App\Filament\Secciones\Concerns\PerteneceAUnaSeccion;
use App\Models\Faq;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FaqResource extends Resource
{
    use PerteneceAUnaSeccion;

    protected static ?string $model = Faq::class;

    protected static ?string $slug = 'preguntas-frecuentes';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $navigationLabel = 'Preguntas frecuentes';

    protected static ?string $modelLabel = 'Pregunta frecuente';

    protected static ?string $pluralModelLabel = 'Preguntas frecuentes';

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 16;

    public static function form(Schema $schema): Schema
    {
        return FaqForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'edit' => EditFaq::route('/{record}/edit'),
        ];
    }

    protected static function seccion(): string
    {
        return PreguntasFrecuentesPage::class;
    }
}
