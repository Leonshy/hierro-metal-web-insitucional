<?php

namespace App\Filament\Resources\Diferenciales\Pages;

use App\Filament\Resources\Diferenciales\DiferencialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDiferencial extends EditRecord
{
    protected static string $resource = DiferencialResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
