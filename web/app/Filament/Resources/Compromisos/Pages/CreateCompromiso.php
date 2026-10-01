<?php

namespace App\Filament\Resources\Compromisos\Pages;

use App\Filament\Resources\Compromisos\CompromisoResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateCompromiso extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = CompromisoResource::class;
}
