<?php

namespace App\Filament\Resources\Pasos\Pages;

use App\Filament\Resources\Pasos\PasoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPaso extends EditRecord
{
    protected static string $resource = PasoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
