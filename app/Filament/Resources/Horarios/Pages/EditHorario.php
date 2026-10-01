<?php

namespace App\Filament\Resources\Horarios\Pages;

use App\Filament\Resources\Horarios\HorarioResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHorario extends EditRecord
{
    use VuelveALaSeccion;

    protected static string $resource = HorarioResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->successRedirectUrl(fn (): string => static::getResource()::urlDeLaSeccion())];
    }
}
