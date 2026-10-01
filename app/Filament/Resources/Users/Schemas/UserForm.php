<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    private const ROLE_LABELS = [
        'administrador' => 'Administrador',
        'editor' => 'Editor de contenido',
        'ventas' => 'Ventas (cotizaciones)',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nombre')->required(),
            TextInput::make('email')->label('Correo')->email()->required()->unique(ignoreRecord: true)
                ->disabled(fn (?User $record): bool => $record?->isProtected() ?? false)
                ->helperText(fn (?User $record): ?string => $record?->isProtected() ? 'Cuenta de mantenimiento: el correo no se puede cambiar.' : null),
            TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->revealable()
                ->required(fn (string $operation) => $operation === 'create')
                ->dehydrated(fn ($state) => filled($state))
                ->helperText('Dejar en blanco para no cambiarla.'),
            Select::make('roles')
                ->label('Rol en el panel')
                ->relationship('roles', 'name')
                ->multiple()
                ->options(fn () => Role::query()->get()->mapWithKeys(
                    fn (Role $role) => [$role->id => self::ROLE_LABELS[$role->name] ?? $role->name]
                ))
                ->required()
                ->preload()
                ->disabled(fn (?User $record): bool => $record?->isProtected() ?? false),
            Toggle::make('is_active')->label('Cuenta activa')->default(true)
                ->disabled(fn (?User $record): bool => $record?->isProtected() ?? false)
                ->helperText(fn (?User $record): string => $record?->isProtected()
                    ? 'Cuenta de mantenimiento: no se puede desactivar.'
                    : 'Desactivarla en vez de borrarla si la persona deja la empresa.'),
        ]);
    }
}
