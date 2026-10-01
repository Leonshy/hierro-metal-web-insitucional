<?php

namespace App\Filament\Resources\Compromisos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompromisosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')->label('Título')->searchable(),
                TextColumn::make('texto')->label('Texto')->limit(70),
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
