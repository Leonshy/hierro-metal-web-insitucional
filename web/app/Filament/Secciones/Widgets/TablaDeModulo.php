<?php

namespace App\Filament\Secciones\Widgets;

use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Model;

/**
 * Tabla de un módulo (familias, pasos, compromisos…) al pie de la pantalla de su sección. Reutiliza las columnas y
 * el orden por arrastre de la tabla original del módulo; agregar y editar abren la pantalla del propio módulo y,
 * al guardar, se vuelve a la sección. Eliminar se hace desde la pantalla de edición.
 */
abstract class TablaDeModulo extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 'full';

    /** @return class-string<resource> */
    abstract protected function recurso(): string;

    /** @return class-string la clase `XxxTable` con las columnas del módulo */
    abstract protected function tabla(): string;

    abstract protected function titulo(): string;

    protected function etiquetaAgregar(): string
    {
        return 'Agregar';
    }

    public function table(Table $table): Table
    {
        $recurso = $this->recurso();

        return $this->tabla()::configure($table)
            ->heading($this->titulo())
            ->query($recurso::getModel()::query())
            ->paginated(false)
            ->toolbarActions([])
            ->headerActions([
                Action::make('agregar')
                    ->label($this->etiquetaAgregar())
                    ->icon('heroicon-m-plus')
                    ->url(fn (): string => $recurso::getUrl('create'))
                    ->visible(fn (): bool => $recurso::canCreate()),
            ])
            ->recordActions([
                Action::make('editar')
                    ->label('Editar')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Model $record): string => $recurso::getUrl('edit', ['record' => $record]))
                    ->visible(fn (Model $record): bool => $recurso::canEdit($record)),
            ]);
    }
}
