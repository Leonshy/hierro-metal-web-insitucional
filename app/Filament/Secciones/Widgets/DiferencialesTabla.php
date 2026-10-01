<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Diferenciales\DiferencialResource;
use App\Filament\Resources\Diferenciales\Tables\DiferencialesTable;

class DiferencialesTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return DiferencialResource::class;
    }

    protected function tabla(): string
    {
        return DiferencialesTable::class;
    }

    protected function titulo(): string
    {
        return 'Diferenciales (franja amarilla)';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar diferencial';
    }
}
