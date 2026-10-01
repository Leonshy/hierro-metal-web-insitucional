<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Horarios\HorarioResource;
use App\Filament\Resources\Horarios\Tables\HorariosTable;

class HorariosTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return HorarioResource::class;
    }

    protected function tabla(): string
    {
        return HorariosTable::class;
    }

    protected function titulo(): string
    {
        return 'Horarios de atención';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar horario';
    }
}
