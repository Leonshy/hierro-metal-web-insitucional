<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Servicios\ServicioResource;
use App\Filament\Resources\Servicios\Tables\ServiciosTable;

class ServiciosTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return ServicioResource::class;
    }

    protected function tabla(): string
    {
        return ServiciosTable::class;
    }

    protected function titulo(): string
    {
        return 'Los servicios';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar servicio';
    }
}
