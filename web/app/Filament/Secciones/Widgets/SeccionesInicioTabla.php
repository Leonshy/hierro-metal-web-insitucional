<?php

namespace App\Filament\Secciones\Widgets;

use App\Models\SeccionInicio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/** Activar o desactivar cada sección de la portada y ordenarlas arrastrando (van debajo de los diferenciales). */
class SeccionesInicioTabla extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Secciones de la portada')
            ->description('Van debajo de los diferenciales, en este orden. Activá o desactivá cada una y arrastrá para cambiar el orden: los números («01 —», «02 —»…) se ajustan solos.')
            ->query(SeccionInicio::query())
            ->columns([
                TextColumn::make('nombre')->label('Sección'),
                ToggleColumn::make('activo')->label('Se muestra'),
            ])
            ->reorderable('orden')
            ->defaultSort('orden')
            ->paginated(false);
    }
}
