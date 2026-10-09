<?php

namespace App\Filament\Resources\Popups\Schemas;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Tables\MediaLibraryTable;
use App\Models\Popup;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PopupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('El pop-up')->schema([
                TextInput::make('nombre')->label('Nombre (sólo para vos)')->required()->maxLength(120)
                    ->helperText('Para reconocerlo en la lista. No se muestra en el sitio. Ej: Promo de octubre.'),
                MediaPicker::make('media_id')->label('Imagen')->required()->tableConfiguration(MediaLibraryTable::class)
                    ->helperText('Es lo único que se ve en el pop-up, así que el texto va dentro de la imagen. Funciona mejor una imagen cuadrada o vertical, de al menos 800 px de ancho.'),
                TextInput::make('texto_alternativo')->label('Qué dice la imagen')->maxLength(200)
                    ->helperText('Para quienes no ven la imagen (lectores de pantalla). Escribí el texto principal del afiche.'),
                TextInput::make('enlace')->label('Enlace al hacer clic')->maxLength(500)
                    ->rule('regex:/^(\/(?!\/)\S*|https?:\/\/\S+)$/')
                    ->validationMessages(['regex' => 'Escribí una ruta del sitio (ej: /contacto) o una dirección completa que empiece con https://.'])
                    ->helperText('Una ruta del sitio (/contacto, /productos/chapas) o una dirección completa (https://…). Si lo dejás vacío, la imagen no es clickeable.'),
                Toggle::make('nueva_pestana')->label('Abrir el enlace en una pestaña nueva')
                    ->helperText('Recomendado para enlaces a otros sitios.'),
            ])->columns(1),

            Section::make('Dónde y cuándo se muestra')->schema([
                Select::make('donde')->label('Dónde')->options(Popup::DONDE)->default('inicio')->required()->native(false)
                    ->helperText('Nunca aparece en la página de contacto, para no interrumpir el pedido de cotización.'),
                Select::make('frecuencia')->label('Cada cuánto')->options(Popup::FRECUENCIAS)->default('sesion')->required()->native(false),
                DateTimePicker::make('desde')->label('Mostrar desde')->seconds(false)->helperText('Vacío = ya mismo.'),
                DateTimePicker::make('hasta')->label('Mostrar hasta')->seconds(false)->after('desde')->helperText('Vacío = sin fecha de fin.'),
                Toggle::make('activo')->label('Activo')->default(true)
                    ->helperText('Si hay varios activos y vigentes, se muestra uno por visita: el que esté primero en la lista.'),
            ])->columns(2),
        ]);
    }
}
