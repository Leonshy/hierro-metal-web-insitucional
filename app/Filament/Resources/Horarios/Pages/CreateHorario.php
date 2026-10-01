<?php

namespace App\Filament\Resources\Horarios\Pages;

use App\Filament\Resources\Horarios\HorarioResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateHorario extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = HorarioResource::class;
}
