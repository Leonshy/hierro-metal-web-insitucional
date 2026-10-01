<?php

namespace App\Filament\Resources\Diferenciales\Schemas;

use App\Rules\MaxWords;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiferencialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titulo')->label('Título')->required()->maxLength(60)
                ->rule(new MaxWords(5))->helperText('2 o 3 palabras. Ej: Stock permanente.'),
            TextInput::make('texto')->label('Texto')->required()->maxLength(120)
                ->rule(new MaxWords(8))->helperText('Frase corta. Ej: Medidas comerciales todo el año.'),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ]);
    }
}
