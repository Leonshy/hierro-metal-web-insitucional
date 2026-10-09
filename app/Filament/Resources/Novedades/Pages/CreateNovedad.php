<?php

namespace App\Filament\Resources\Novedades\Pages;

use App\Filament\Resources\Novedades\NovedadResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateNovedad extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = NovedadResource::class;
}
