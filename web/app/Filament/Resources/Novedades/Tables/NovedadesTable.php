<?php

namespace App\Filament\Resources\Novedades\Tables;

use App\Models\Novedad;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NovedadesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')->label('Título')->searchable()->wrap(),
                TextColumn::make('publicada_en')->label('Publicación')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('estado')->label('Estado')->badge()
                    ->getStateUsing(fn (Novedad $novedad): string => match (true) {
                        ! $novedad->activo => 'Oculta',
                        $novedad->publicada_en === null => 'Sin fecha',
                        $novedad->publicada_en->isFuture() => 'Programada',
                        default => 'Publicada',
                    })
                    ->color(fn (string $state): string => $state === 'Publicada' ? 'success' : ($state === 'Programada' ? 'info' : 'gray')),
            ])
            ->defaultSort('publicada_en', 'desc')
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()->label('Eliminar')]),
            ]);
    }
}
