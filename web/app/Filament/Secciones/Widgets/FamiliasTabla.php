<?php

namespace App\Filament\Secciones\Widgets;

use App\Filament\Resources\Familias\FamiliaResource;
use App\Filament\Resources\Familias\Tables\FamiliasTable;

class FamiliasTabla extends TablaDeModulo
{
    protected function recurso(): string
    {
        return FamiliaResource::class;
    }

    protected function tabla(): string
    {
        return FamiliasTable::class;
    }

    protected function titulo(): string
    {
        return 'Las familias de productos';
    }

    protected function etiquetaAgregar(): string
    {
        return 'Agregar familia';
    }
}
