<?php

namespace App\Filament\Resources\Familias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FamiliasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Familia')->searchable(),
                TextColumn::make('slug')->label('Dirección')->prefix('/productos/')->color('gray'),
                TextColumn::make('lineas_count')->label('Líneas')->counts('lineas')->badge(),
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
