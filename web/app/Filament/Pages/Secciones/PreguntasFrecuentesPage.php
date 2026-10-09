<?php

namespace App\Filament\Pages\Secciones;

use App\Filament\Secciones\SeccionPage;
use App\Filament\Secciones\Widgets\FaqsTabla;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class PreguntasFrecuentesPage extends SeccionPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $navigationLabel = 'Preguntas frecuentes';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Preguntas frecuentes';

    protected static function paginaSlug(): string
    {
        return 'preguntas-frecuentes';
    }

    protected function getFooterWidgets(): array
    {
        return [FaqsTabla::class];
    }
}
