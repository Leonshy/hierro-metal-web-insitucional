<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Orden manual por arrastre (columna `orden`) y bandera `activo`.
 * Un registro nuevo queda al final de la lista.
 */
trait Ordenable
{
    protected static function bootOrdenable(): void
    {
        static::creating(function ($modelo): void {
            if (! $modelo->orden) {
                $modelo->orden = (int) static::query()->max('orden') + 1;
            }
        });
    }

    public function scopeOrdenados(Builder $query): Builder
    {
        return $query->orderBy('orden')->orderBy('id');
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
