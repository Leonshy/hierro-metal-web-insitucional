<?php

namespace App\Filament\Resources\Horarios\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class HorarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('etiqueta')->label('Etiqueta')->required()->maxLength(60)
                ->helperText('Ej: Lunes a viernes, Sábados, Domingos y feriados.'),
            CheckboxList::make('dias')->label('Días')->required()->columns(4)
                ->options([1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo']),
            Toggle::make('cerrado')->label('Cerrado todo el día')->live(),
            TimePicker::make('abre')->label('Abre')->seconds(false)->required(fn (Get $get): bool => ! $get('cerrado'))
                ->visible(fn (Get $get): bool => ! $get('cerrado')),
            TimePicker::make('cierra')->label('Cierra')->seconds(false)->required(fn (Get $get): bool => ! $get('cerrado'))
                ->after('abre')->visible(fn (Get $get): bool => ! $get('cerrado')),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ]);
    }
}
