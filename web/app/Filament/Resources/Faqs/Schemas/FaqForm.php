<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Rules\MaxWords;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('pregunta')->label('Pregunta')->required()->maxLength(200)
                ->rule(new MaxWords(12))->helperText('De 2 a 8 palabras. Ej: ¿Cortan el material a medida?'),
            RichEditor::make('respuesta')->label('Respuesta')->required()
                ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList'])
                ->helperText('Entre 21 y 55 palabras. Los enlaces a otras páginas se escriben como /servicios o /contacto.'),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ]);
    }
}
