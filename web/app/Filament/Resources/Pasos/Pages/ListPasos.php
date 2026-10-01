<?php

namespace App\Filament\Resources\Pasos\Pages;

use App\Filament\Resources\Pasos\PasoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPasos extends ListRecords
{
    protected static string $resource = PasoResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
