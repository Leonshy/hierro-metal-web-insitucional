<?php

namespace App\Filament\Resources\Cotizaciones\Schemas;

use App\Models\Cotizacion;
use App\Models\CotizacionAdjunto;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CotizacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pedido')
                ->description('Lo que escribió la persona. No se edita.')
                ->schema([
                    TextInput::make('nombre')->label('Nombre')->disabled()->dehydrated(false),
                    TextInput::make('empresa')->label('Empresa u obra')->disabled()->dehydrated(false),
                    TextInput::make('ci_ruc')->label('CI o RUC')->disabled()->dehydrated(false),
                    TextInput::make('telefono')->label('Teléfono o WhatsApp')->disabled()->dehydrated(false),
                    TextInput::make('email')->label('Correo')->disabled()->dehydrated(false),
                    TextInput::make('rubro')->label('Rubro')->disabled()->dehydrated(false),
                    TextInput::make('origen')->label('Página desde la que escribió')->disabled()->dehydrated(false),
                    Textarea::make('mensaje')->label('Lista de materiales o detalle')->rows(8)->disabled()->dehydrated(false)->columnSpanFull(),
                    Placeholder::make('adjuntos')->label('Archivos adjuntos')->columnSpanFull()
                        ->content(fn (?Cotizacion $record): HtmlString => self::listaDeAdjuntos($record)),
                ])->columns(2),

            Section::make('Seguimiento')->schema([
                Select::make('estado')->label('Estado')->options(Cotizacion::ESTADOS)->required()->native(false),
                Select::make('asignado_a')->label('Atiende')->searchable()->placeholder('Sin asignar')
                    ->options(fn (): array => User::query()->where('is_active', true)->role(['ventas', 'administrador'])->orderBy('name')->pluck('name', 'id')->all()),
                Textarea::make('notas_internas')->label('Notas internas')->rows(4)->maxLength(4000)->columnSpanFull()
                    ->helperText('Sólo las ve el equipo. No se le muestran a quien pidió la cotización.'),
                Placeholder::make('aviso')->label('Aviso por correo')->columnSpanFull()
                    ->content(fn (?Cotizacion $record): string => match (true) {
                        $record === null => '',
                        $record->esSpam() => 'No corresponde: se marcó como spam.',
                        $record->mail_enviado_at !== null => 'Enviado el '.$record->mail_enviado_at->timezone('America/Asuncion')->format('d/m/Y H:i').'.',
                        default => 'Todavía no salió ('.$record->mail_intentos.' intentos). El pedido está guardado; se reintenta solo. Revisá el correo de notificación en Configuración.',
                    }),
            ])->columns(2),
        ]);
    }

    private static function listaDeAdjuntos(?Cotizacion $cotizacion): HtmlString
    {
        if (! $cotizacion || $cotizacion->adjuntos->isEmpty()) {
            return new HtmlString('<span>Sin archivos.</span>');
        }

        $items = $cotizacion->adjuntos->map(fn (CotizacionAdjunto $adjunto): string => sprintf(
            '<li><a href="%s" style="text-decoration:underline">%s</a> <span style="opacity:.7">(%s)</span></li>',
            e(route('cotizaciones.adjunto', $adjunto)),
            e($adjunto->nombre_original),
            e($adjunto->tamanoLegible()),
        ))->implode('');

        return new HtmlString('<ul style="list-style:disc;padding-left:1.25rem">'.$items.'</ul>');
    }
}
