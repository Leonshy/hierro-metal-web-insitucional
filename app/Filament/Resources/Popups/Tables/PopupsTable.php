<?php

namespace App\Filament\Resources\Popups\Tables;

use App\Models\Popup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PopupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagen')->label('Imagen')->height(56)
                    ->getStateUsing(fn (Popup $popup): ?string => $popup->media?->conversionUrl('small') ?? $popup->media?->url()),
                TextColumn::make('nombre')->label('Nombre')->searchable()->wrap(),
                TextColumn::make('enlace')->label('Lleva a')->placeholder('Sin enlace')->limit(40)->color('gray'),
                TextColumn::make('donde')->label('Dónde')->badge()->formatStateUsing(fn (string $state): string => Popup::DONDE[$state] ?? $state),
                TextColumn::make('vigencia')->label('Vigencia')
                    ->getStateUsing(fn (Popup $popup): string => match (true) {
                        $popup->desde && $popup->hasta => $popup->desde->format('d/m/Y').' al '.$popup->hasta->format('d/m/Y'),
                        (bool) $popup->desde => 'Desde el '.$popup->desde->format('d/m/Y'),
                        (bool) $popup->hasta => 'Hasta el '.$popup->hasta->format('d/m/Y'),
                        default => 'Sin fechas',
                    }),
                ToggleColumn::make('activo')->label('Activo'),
            ])
            ->reorderable('orden')
            ->defaultSort('orden')
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()->label('Eliminar')]),
            ]);
    }
}
