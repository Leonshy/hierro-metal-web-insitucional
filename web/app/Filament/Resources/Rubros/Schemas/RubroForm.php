<?php

namespace App\Filament\Resources\Rubros\Schemas;

use App\Rules\MaxWords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RubroForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')->label('Nombre')->required()->maxLength(100)->rule(new MaxWords(8))
                ->helperText('Es lo que ve la persona en «¿Qué necesitás?». Ej: Chapas de acero.'),
            TextInput::make('slug')->label('Código en la dirección')->required()->maxLength(60)
                ->unique(ignoreRecord: true)->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                ->validationMessages(['regex' => 'Sólo minúsculas, números y guiones. Ej: chapas.'])
                ->helperText('Viaja en la dirección del formulario: /contacto?rubro=chapas. Usá el mismo código que la familia.'),
            Select::make('familia_id')->label('Familia relacionada (opcional)')->relationship('familia', 'nombre')->searchable()->preload(),
            Select::make('servicio_id')->label('Servicio relacionado (opcional)')->relationship('servicio', 'nombre')->searchable()->preload(),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ]);
    }
}
