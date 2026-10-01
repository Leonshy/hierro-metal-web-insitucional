<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre')->searchable()
                    ->icon(fn (User $record): ?string => $record->isProtected() ? 'heroicon-m-lock-closed' : null)
                    ->description(fn (User $record): ?string => $record->isProtected() ? 'Cuenta de mantenimiento · no se puede eliminar' : null),
                TextColumn::make('email')->label('Correo')->searchable(),
                TextColumn::make('roles.name')->label('Rol')->badge(),
                IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $record): bool => ! $record->isProtected())
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()->label('Eliminar')])]);
    }
}
