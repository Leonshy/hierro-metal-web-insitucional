<?php

namespace App\Filament\Resources\Pasos\Pages;

use App\Filament\Resources\Pasos\PasoResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreatePaso extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = PasoResource::class;
}
