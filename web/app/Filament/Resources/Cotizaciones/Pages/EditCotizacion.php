<?php

namespace App\Filament\Resources\Cotizaciones\Pages;

use App\Filament\Resources\Cotizaciones\CotizacionResource;
use App\Models\Cotizacion;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditCotizacion extends EditRecord
{
    protected static string $resource = CotizacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('whatsapp')
                ->label('Responder por WhatsApp')
                ->icon(Heroicon::ChatBubbleLeftRight)
                ->color('success')
                ->url(fn (Cotizacion $record): string => $record->whatsappUrl(), shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }
}
