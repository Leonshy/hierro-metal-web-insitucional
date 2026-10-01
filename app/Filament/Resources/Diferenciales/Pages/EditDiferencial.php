<?php

namespace App\Filament\Resources\Diferenciales\Pages;

use App\Filament\Resources\Diferenciales\DiferencialResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDiferencial extends EditRecord
{
    use VuelveALaSeccion;

    protected static string $resource = DiferencialResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->successRedirectUrl(fn (): string => static::getResource()::urlDeLaSeccion())];
    }
}
