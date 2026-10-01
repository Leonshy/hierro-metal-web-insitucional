<?php

namespace App\Filament\Resources\Servicios\Pages;

use App\Filament\Resources\Servicios\ServicioResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateServicio extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = ServicioResource::class;
}
