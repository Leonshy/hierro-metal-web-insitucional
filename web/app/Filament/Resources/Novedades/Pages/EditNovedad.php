<?php

namespace App\Filament\Resources\Novedades\Pages;

use App\Filament\Resources\Novedades\NovedadResource;
use App\Filament\Secciones\Concerns\VuelveALaSeccion;
use App\Models\Novedad;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNovedad extends EditRecord
{
    use VuelveALaSeccion;

    protected static string $resource = NovedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ver')
                ->label(fn (Novedad $record): string => $record->estaPublicada() ? 'Ver en el sitio' : 'Vista previa')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn (Novedad $record): string => route('novedades.show', $record->slug))
                ->openUrlInNewTab(),
            DeleteAction::make()->successRedirectUrl(fn (): string => static::getResource()::urlDeLaSeccion()),
        ];
    }
}
