<?php

namespace App\Filament\Resources\Servicios\Schemas;

use App\Rules\MaxItems;
use App\Rules\MaxWords;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ServicioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')->label('Nombre')->required()->maxLength(100)
                ->rule(new MaxWords(6))->helperText('De 1 a 4 palabras. Ej: Cortes a medida.'),
            Textarea::make('descripcion')->label('Descripción')->required()->rows(4)->maxLength(400)
                ->rule(new MaxWords(45))->helperText('Entre 14 y 33 palabras.'),
            TagsInput::make('usos')->label('Usos')->helperText('2 a 4 etiquetas cortas. Ej: Chapas, Barras, Perfiles.')
                ->rule(new MaxItems(4)),
            Section::make('En el inicio')
                ->description('Si lo destacás, aparece como tarjeta en el bloque «Trabajamos el material por vos» del inicio (hasta 3).')
                ->schema([
                    Toggle::make('destacado_home')->label('Destacar en el inicio')->live(),
                    TextInput::make('titulo_home')->label('Título en el inicio')->maxLength(80)
                        ->helperText('Por ejemplo «Cortes y plegados» agrupa dos servicios.')
                        ->visible(fn (Get $get): bool => (bool) $get('destacado_home')),
                    TextInput::make('resumen_home')->label('Resumen en el inicio')->maxLength(200)
                        ->rule(new MaxWords(14))
                        ->visible(fn (Get $get): bool => (bool) $get('destacado_home')),
                ]),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ]);
    }
}
