<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Rubros\RubroResource;
use App\Filament\Resources\Rubros\Tables\RubrosTable;

class RubrosTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return RubroResource::class;
    }

    protected function tabla(): string
    {
        return RubrosTable::class;
    }

    protected function titulo(): string
    {
        return 'Rubros del formulario de cotización';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar rubro';
    }
}
