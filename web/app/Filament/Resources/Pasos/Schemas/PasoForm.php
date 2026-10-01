<?php

namespace App\Filament\Resources\Pasos\Schemas;

use App\Rules\MaxWords;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PasoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titulo')->label('Título')->required()->maxLength(100)
                ->rule(new MaxWords(6))->helperText('Ej: Nos mandás el pedido.'),
            Textarea::make('texto')->label('Texto')->required()->rows(3)->maxLength(300)
                ->rule(new MaxWords(25))->helperText('Entre 12 y 18 palabras.'),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ]);
    }
}
