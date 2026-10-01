<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Compromisos\CompromisoResource;
use App\Filament\Resources\Compromisos\Tables\CompromisosTable;

class CompromisosTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return CompromisoResource::class;
    }

    protected function tabla(): string
    {
        return CompromisosTable::class;
    }

    protected function titulo(): string
    {
        return 'Compromisos de calidad';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar compromiso';
    }
}
