<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Blocks\PageBlocks;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Tables\MediaLibraryTable;
use App\Models\Page;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        // `EditRecord`/`CreateRecord` imponen `columns(2)` al schema raíz salvo
        // que el propio formulario ya declare los suyos (`hasCustomColumns()`)
        // — sin este `->columns(3)` acá, el Group de abajo quedaba metido
        // dentro de una sola celda de esa grilla ajena de 2 columnas, dejando
        // la otra mitad de la pantalla vacía en vez de usar el ancho completo.
        return $schema
            ->columns(3)
            ->components([
                // Contenido de la página, uno abajo del otro en la columna
                // izquierda: un Group apila sus hijos por su propia altura,
                // sin esperar a que la columna de configuraciones "complete
                // la fila" (eso era lo que dejaba huecos verticales).
                Group::make([
                    Section::make('Título')
                        ->schema([
                            TextInput::make('title.es')
                                ->label('Título')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, ?Page $record) {
                                    if (! $record) {
                                        $set('slug', Str::slug($state));
                                    }
                                })
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('Dirección web de la página (URL)')
                                ->helperText('Se genera sola a partir del título, pero podés editarla. Ej: "institucion/historia"')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->rules([fn (): \Closure => self::reglaDireccionLibre()])
                                ->maxLength(255),
                        ]),

                    Section::make('Contenido')
                        ->description('El encabezado (título y bajada) y los textos, en el orden en que se muestran.')
                        ->schema([
                            Builder::make('blocks')
                                ->hiddenLabel()
                                ->blocks([PageBlocks::bloque('hero'), PageBlocks::bloque('texto')])
                                ->addActionLabel('Agregar bloque')
                                ->collapsible()
                                ->blockNumbers(false),
                        ]),
                ])->columnSpan(2),

                // Configuración de la página, uno abajo del otro en la
                // columna derecha.
                Group::make([
                    Section::make('Publicación')
                        ->schema([
                            Select::make('status')
                                ->label('Estado')
                                ->options([
                                    'draft' => 'Borrador (no visible en el sitio)',
                                    'published' => 'Publicada',
                                    'archived' => 'Archivada (fuera de menús, visible solo por enlace directo)',
                                ])
                                ->default('draft')
                                ->required(),
                        ]),

                    Section::make('Buscadores (SEO)')
                        ->description('Cómo se ve esta página en Google. Si lo dejás vacío, se usa el título de la página.')
                        ->collapsed()
                        ->schema([
                            TextInput::make('seo_title.es')
                                ->label('Título para buscadores')
                                ->maxLength(60),
                            Textarea::make('seo_description.es')
                                ->label('Descripción para buscadores')
                                ->maxLength(160)
                                ->rows(2),
                            Toggle::make('is_indexable')
                                ->label('Permitir que Google indexe esta página')
                                ->default(true),
                            TextInput::make('canonical_url')
                                ->label('URL canónica (avanzado)')
                                ->helperText('Dejar vacío salvo que esta página duplique el contenido de otra — ahí sí cargá la URL completa de la página "original".')
                                ->url()
                                ->maxLength(255),
                            MediaPicker::make('seo_image_id')
                                ->label('Imagen para compartir (Open Graph)')
                                ->helperText('La imagen que se muestra al compartir esta página en redes sociales. Si la dejás vacía, se usa la imagen por defecto del sitio.')
                                ->tableConfiguration(MediaLibraryTable::class),
                        ]),
                ])->columnSpan(1),
            ]);
    }

    /**
     * Una página «libre» no puede tomar una dirección que ya usa el sitio: una sección (Productos, Servicios…), la
     * ficha de una familia, el panel o los archivos del sistema.
     */
    private static function reglaDireccionLibre(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            $direccion = trim(mb_strtolower((string) $value), '/');
            $primero = explode('/', $direccion)[0];
            $reservadas = [...Page::SECCIONES, 'panel', trim((string) config('sitio.admin_path'), '/'), 'storage', 'build', 'livewire', 'catalogo'];

            if (in_array($direccion, Page::SECCIONES, true) || in_array($primero, array_filter($reservadas), true) || $direccion === 'contacto/gracias') {
                $fail('Esa dirección la usa el propio sitio. Elegí otra (por ejemplo «terminos-y-condiciones»).');
            }
        };
    }
}
