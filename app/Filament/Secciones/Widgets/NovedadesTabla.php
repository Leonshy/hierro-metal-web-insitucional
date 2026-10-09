<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Novedades\NovedadResource;
use App\Filament\Resources\Novedades\Tables\NovedadesTable;

class NovedadesTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return NovedadResource::class;
    }

    protected function tabla(): string
    {
        return NovedadesTable::class;
    }

    protected function titulo(): string
    {
        return 'Las novedades';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar novedad';
    }
}
