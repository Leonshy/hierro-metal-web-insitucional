<?php

namespace App\Filament\Resources\Rubros\Pages;

use App\Filament\Resources\Rubros\RubroResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRubro extends EditRecord
{
    use VuelveALaSeccion;

    protected static string $resource = RubroResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->successRedirectUrl(fn (): string => static::getResource()::urlDeLaSeccion())];
    }
}
