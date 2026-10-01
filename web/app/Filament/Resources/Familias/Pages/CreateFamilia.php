<?php

namespace App\Filament\Resources\Familias\Pages;

use App\Filament\Resources\Familias\FamiliaResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateFamilia extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = FamiliaResource::class;
}
