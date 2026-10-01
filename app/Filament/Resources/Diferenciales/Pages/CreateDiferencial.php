<?php

namespace App\Filament\Resources\Diferenciales\Pages;

use App\Filament\Resources\Diferenciales\DiferencialResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateDiferencial extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = DiferencialResource::class;
}
