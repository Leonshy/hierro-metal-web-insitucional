<?php

namespace App\Filament\Resources\Compromisos\Pages;

use App\Filament\Resources\Compromisos\CompromisoResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompromiso extends EditRecord
{
    use VuelveALaSeccion;

    protected static string $resource = CompromisoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->successRedirectUrl(fn (): string => static::getResource()::urlDeLaSeccion())];
    }
}
