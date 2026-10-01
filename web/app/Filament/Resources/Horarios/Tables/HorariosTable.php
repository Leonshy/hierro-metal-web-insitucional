<?php

namespace App\Filament\Resources\Horarios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HorariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('etiqueta')->label('Horario')->searchable(),
                TextColumn::make('abre')->label('Abre')->time('H:i'),
                TextColumn::make('cierra')->label('Cierra')->time('H:i'),
                IconColumn::make('cerrado')->label('Cerrado')->boolean(),
                IconColumn::make('activo')->label('Visible')->boolean(),
            ])
            ->reorderable('orden')
            ->defaultSort('orden')
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()->label('Eliminar')]),
            ]);
    }
}
