<?php

namespace App\Filament\Resources\Pasos\Pages;

use App\Filament\Resources\Pasos\PasoResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaso extends EditRecord
{
    use VuelveALaSeccion;

    protected static string $resource = PasoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->successRedirectUrl(fn (): string => static::getResource()::urlDeLaSeccion())];
    }
}
