<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Pasos\PasoResource;
use App\Filament\Resources\Pasos\Tables\PasosTable;

class PasosTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return PasoResource::class;
    }

    protected function tabla(): string
    {
        return PasosTable::class;
    }

    protected function titulo(): string
    {
        return 'Pasos «De tu plano a la obra»';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar paso';
    }
}
