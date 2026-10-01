<?php

namespace App\Filament\Resources\Diferenciales\Pages;

use App\Filament\Resources\Diferenciales\DiferencialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiferenciales extends ListRecords
{
    protected static string $resource = DiferencialResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
