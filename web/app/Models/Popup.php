<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Pop-up de imagen con enlace. Se muestra a quien visita el sitio mientras esté activo y dentro de sus fechas;
 * si hay más de uno vigente se muestra uno por visita (el primero en el orden del panel). El pop-up nunca aparece
 * en /contacto, donde la persona está llenando el formulario de cotización.
 *
 * @property string $nombre
 * @property int|null $media_id
 * @property string|null $texto_alternativo
 * @property string|null $enlace
 * @property bool $nueva_pestana
 * @property string $donde
 * @property string $frecuencia
 * @property Carbon|null $desde
 * @property Carbon|null $hasta
 * @property bool $activo
 * @property Carbon|null $updated_at
 * @property-read Media|null $media
 */
#[Fillable(['nombre', 'media_id', 'texto_alternativo', 'enlace', 'nueva_pestana', 'donde', 'frecuencia', 'desde', 'hasta', 'activo', 'orden'])]
class Popup extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'popups';

    public const DONDE = [
        'inicio' => 'Sólo en el inicio',
        'todas' => 'En todo el sitio',
    ];

    public const FRECUENCIAS = [
        'sesion' => 'Una vez por visita',
        'dia' => 'Una vez al día',
        'siempre' => 'Cada vez que se abre una página',
    ];

    protected function casts(): array
    {
        return ['activo' => 'boolean', 'nueva_pestana' => 'boolean', 'desde' => 'datetime', 'hasta' => 'datetime'];
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /** Activos, con imagen y dentro de su período de vigencia. */
    public function scopeVigentes(Builder $query): Builder
    {
        return $query->where('activo', true)
            ->whereNotNull('media_id')
            ->where(fn (Builder $q) => $q->whereNull('desde')->orWhere('desde', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('hasta')->orWhere('hasta', '>=', now()));
    }

    /** El pop-up que corresponde mostrar en esta dirección, o null si ninguno. */
    public static function paraMostrar(bool $esInicio): ?self
    {
        return static::query()
            ->with('media')
            ->vigentes()
            ->when(! $esInicio, fn (Builder $q) => $q->where('donde', 'todas'))
            ->ordenados()
            ->first();
    }

    /** Dirección lista para el `href`: las rutas del sitio (/contacto) se completan con el dominio. */
    public function enlaceCompleto(): ?string
    {
        if (blank($this->enlace)) {
            return null;
        }

        return str_starts_with($this->enlace, '/') ? url($this->enlace) : $this->enlace;
    }

    public function esExterno(): bool
    {
        return $this->enlace !== null && ! str_starts_with($this->enlace, '/');
    }
}
