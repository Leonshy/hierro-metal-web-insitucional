<?php

namespace App\Filament\Resources\Faqs\Pages;

use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    use VuelveALaSeccion;

    protected static string $resource = FaqResource::class;
}
