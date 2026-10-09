<?php

namespace App\Filament\Resources\Novedades\Schemas;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Tables\MediaLibraryTable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NovedadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('La novedad')->schema([
                TextInput::make('titulo')->label('Título')->required()->maxLength(160)->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                        // Propone la dirección a partir del título, mientras no se haya escrito una a mano.
                        if (blank($get('slug'))) {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                TextInput::make('slug')->label('Dirección web')->required()->maxLength(120)
                    ->unique(ignoreRecord: true)->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                    ->validationMessages(['regex' => 'Sólo minúsculas, números y guiones. Ej: llegada-de-chapas.'])
                    ->helperText('Define la dirección: /novedades/llegada-de-chapas. No la cambies una vez publicada: se rompen los enlaces.'),
                Textarea::make('resumen')->label('Resumen')->required()->rows(3)->maxLength(300)
                    ->helperText('Aparece en las tarjetas del inicio y del listado, y debajo del título. Una o dos oraciones.'),
                MediaPicker::make('media_id')->label('Foto de portada (opcional)')->tableConfiguration(MediaLibraryTable::class)
                    ->helperText('Se ve en la tarjeta y arriba de la nota. Mejor horizontal.'),
                RichEditor::make('contenido')->label('Texto de la novedad')->required()
                    ->toolbarButtons(['bold', 'italic', 'h2', 'h3', 'link', 'bulletList', 'orderedList', 'blockquote'])
                    ->columnSpanFull(),
            ])->columns(1),

            Section::make('Publicación')->schema([
                DateTimePicker::make('publicada_en')->label('Fecha de publicación')->seconds(false)->default(now())->required()
                    ->helperText('Si la ponés a futuro, la novedad queda programada y aparece sola ese día.'),
                Toggle::make('activo')->label('Publicada')->default(true)
                    ->helperText('Apagala para esconder la novedad sin borrarla.'),
            ])->columns(2),

            Section::make('Buscadores (SEO)')->collapsed()->schema([
                TextInput::make('seo_titulo')->label('Título para Google')->maxLength(60)->helperText('Si lo dejás vacío se usa el título de la novedad.'),
                Textarea::make('seo_descripcion')->label('Descripción para Google')->rows(2)->maxLength(160)->helperText('Si lo dejás vacío se usa el resumen.'),
            ]),
        ]);
    }
}
