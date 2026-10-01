<?php

namespace App\Filament\Resources\Vendedores\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VendedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')->label('Nombre y apellido')->required()->maxLength(100),
            TextInput::make('telefono')->label('Teléfono o WhatsApp')->required()->tel()->maxLength(40)
                ->helperText('Se carga pero NO se publica hasta que lo actives: son datos personales de empleados y necesitan su consentimiento.'),
            Toggle::make('activo')->label('Visible en el sitio')->default(false),
        ]);
    }
}
