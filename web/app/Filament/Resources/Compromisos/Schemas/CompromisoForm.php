<?php

namespace App\Filament\Resources\Compromisos\Schemas;

use App\Rules\MaxWords;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompromisoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titulo')->label('Título')->required()->maxLength(100)
                ->rule(new MaxWords(6))->helperText('De 2 a 4 palabras. Ej: Materia prima certificada.'),
            Textarea::make('texto')->label('Texto')->required()->rows(4)->maxLength(400)
                ->rule(new MaxWords(30))->helperText('Alrededor de 20 palabras.'),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ]);
    }
}
