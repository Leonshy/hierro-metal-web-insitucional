<?php

namespace App\Filament\Resources\Familias\RelationManagers;

use App\Models\Linea;
use App\Rules\MaxItems;
use App\Rules\MaxWords;
use Closure;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/** Líneas de la familia y sus tablas de medidas (catálogo 2026 del cliente). */
class LineasRelationManager extends RelationManager
{
    protected static string $relationship = 'lineas';

    protected static ?string $title = 'Líneas y medidas';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nombre')->label('Nombre de la línea')->required()->maxLength(200)
                ->rule(new MaxWords(24))->columnSpanFull()
                ->helperText('Hasta 24 palabras. Lo habitual son 2 a 6. Ej: Chapas galvanizadas.'),
            Textarea::make('descripcion')->label('Descripción')->required()->rows(3)->maxLength(400)
                ->rule(new MaxWords(25))->columnSpanFull()->helperText('Entre 9 y 18 palabras.'),
            TagsInput::make('usos')->label('Usos')->rule(new MaxItems(4))->columnSpanFull()
                ->helperText('2 a 4 etiquetas cortas (hasta 3 palabras cada una). Ej: Techos, Galpones.'),
            Repeater::make('medidas')->label('Tablas de medidas')->columnSpanFull()
                ->addActionLabel('Agregar tabla de medidas')->collapsed()->reorderable()
                ->itemLabel(fn (array $state): string => filled($state['titulo'] ?? null) ? $state['titulo'] : 'Tabla de medidas')
                ->helperText('Una línea puede tener varias tablas (por ejemplo, laminadas en frío y en caliente). Sin tablas, la ficha muestra «Consultanos las medidas disponibles y el precio».')
                ->schema([
                    TextInput::make('titulo')->label('Título de la tabla (opcional)')->maxLength(60)->rule(new MaxWords(4)),
                    Select::make('modo')->label('Formato')->options(['tabla' => 'Tabla', 'lista' => 'Lista (texto largo en cada celda)'])
                        ->default('tabla')->required(),
                    TagsInput::make('columnas')->label('Columnas')->required()->rule(new MaxItems(6))->columnSpanFull()
                        ->helperText('Escribí cada columna y apretá Enter. Ej: Espesor, Largo (mm), Ancho (mm).'),
                    Textarea::make('filas_texto')->label('Filas')->required()->rows(8)->columnSpanFull()
                        ->helperText('Una fila por renglón. Podés pegar directo desde Excel (celdas separadas por tabulación) o separar las celdas con punto y coma.')
                        ->rule(fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                            $filas = Linea::parseFilas((string) $value);
                            $columnas = count((array) $get('columnas'));

                            if (count($filas) > 60) {
                                $fail('Cargá hasta 60 filas por tabla.');
                            }

                            foreach ($filas as $i => $fila) {
                                if (count($fila) !== $columnas) {
                                    $fail('La fila '.($i + 1).' tiene '.count($fila)." celdas y la tabla tiene {$columnas} columnas.");

                                    return;
                                }
                            }
                        }),
                ])->columns(2),
            TextInput::make('nota_medidas')->label('Nota bajo las tablas (opcional)')->maxLength(300)->columnSpanFull()
                ->rule(new MaxWords(25))->helperText('Ej: Realizamos servicios de cortes sobre medida según la necesidad.'),
            Toggle::make('activo')->label('Visible en el sitio')->default(true),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')->label('Línea')->wrap()->searchable(),
                TextColumn::make('medidas')->label('Medidas')->badge()
                    ->state(fn (Linea $record): string => $record->tieneMedidas() ? count($record->tablas()).' tabla(s)' : 'Sin tablas')
                    ->color(fn (Linea $record): string => $record->tieneMedidas() ? 'success' : 'gray'),
                IconColumn::make('activo')->label('Visible')->boolean(),
            ])
            ->reorderable('orden')
            ->defaultSort('orden')
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
