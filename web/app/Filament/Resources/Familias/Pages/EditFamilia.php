<?php

namespace App\Filament\Resources\Familias\Pages;

use App\Filament\Resources\Familias\FamiliaResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFamilia extends EditRecord
{
    use VuelveALaSeccion;

    protected static string $resource = FamiliaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->successRedirectUrl(fn (): string => static::getResource()::urlDeLaSeccion())];
    }
}
