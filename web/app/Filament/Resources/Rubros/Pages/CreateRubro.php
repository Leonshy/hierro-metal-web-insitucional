<?php

namespace App\Filament\Resources\Rubros\Pages;

use App\Filament\Resources\Rubros\RubroResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateRubro extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = RubroResource::class;
}
