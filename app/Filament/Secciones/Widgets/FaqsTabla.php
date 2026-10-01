<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Faqs\Tables\FaqsTable;

class FaqsTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return FaqResource::class;
    }

    protected function tabla(): string
    {
        return FaqsTable::class;
    }

    protected function titulo(): string
    {
        return 'Las preguntas frecuentes';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar pregunta';
    }
}
