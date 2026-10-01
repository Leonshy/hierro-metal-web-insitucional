<?php

namespace App\Filament\Resources\Familias\Schemas;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Tables\MediaLibraryTable;
use App\Models\Familia;
use App\Rules\MaxWords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FamiliaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos de la familia')->schema([
                TextInput::make('nombre')->label('Nombre')->required()->maxLength(80)
                    ->rule(new MaxWords(6))->helperText('De 2 a 4 palabras. Ej: Chapas de acero.'),
                TextInput::make('slug')->label('Dirección web')->required()->maxLength(40)
                    ->unique(ignoreRecord: true)->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                    ->validationMessages(['regex' => 'Sólo minúsculas, números y guiones. Ej: chapas.'])
                    ->helperText('Define la dirección: /productos/chapas. No la cambies una vez publicada: se rompen los enlaces.'),
                Textarea::make('resumen_home')->label('Resumen para el inicio')->required()->rows(2)->maxLength(200)
                    ->rule(new MaxWords(22))->helperText('Entre 13 y 16 palabras. Aparece en la tarjeta de la familia.'),
                Textarea::make('bajada')->label('Bajada de la ficha')->required()->rows(3)->maxLength(400)
                    ->rule(new MaxWords(40))->helperText('Entre 20 y 30 palabras. Aparece bajo el título de la ficha.'),
                Select::make('ilustracion')->label('Ilustración')->options(Familia::ILUSTRACIONES)->default('ninguna')->required()
                    ->helperText('Son las ilustraciones de secciones de acero del cliente.'),
                MediaPicker::make('media_id')->label('Foto propia (opcional)')->tableConfiguration(MediaLibraryTable::class)
                    ->helperText('Si el cliente entrega fotos, conviven con la ilustración. No es obligatoria.'),
                Toggle::make('activo')->label('Visible en el sitio')->default(true),
            ])->columns(2),

            Section::make('WhatsApp y buscadores')->collapsed()->schema([
                TextInput::make('mensaje_whatsapp')->label('Mensaje de WhatsApp de la ficha')->maxLength(200)
                    ->rule(new MaxWords(20))->helperText('Si lo dejás vacío: «Hola, quiero cotizar {nombre}.»'),
                TextInput::make('seo_titulo')->label('Título para Google')->maxLength(60),
                Textarea::make('seo_descripcion')->label('Descripción para Google')->rows(2)->maxLength(160),
            ]),
        ]);
    }
}
