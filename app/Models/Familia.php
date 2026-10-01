<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Familia del catálogo (Chapas, Perfiles, Tubos, Varillas, Accesorios).
 * Cada una tiene su ficha propia en /productos/{slug}.
 */
#[Fillable(['slug', 'nombre', 'resumen_home', 'bajada', 'ilustracion', 'media_id', 'mensaje_whatsapp', 'seo_titulo', 'seo_descripcion', 'orden', 'activo'])]
class Familia extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'familias';

    /** Ilustraciones SVG del cliente (`referencia/assets`). */
    public const ILUSTRACIONES = [
        'chapas' => 'Chapas',
        'perfiles' => 'Perfiles',
        'tubos' => 'Tubos y caños',
        'varillas' => 'Varillas y barras',
        'accesorios' => 'Accesorios de cañería',
        'ninguna' => 'Sin ilustración',
    ];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function lineas(): HasMany
    {
        return $this->hasMany(Linea::class)->orderBy('orden')->orderBy('id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /** Posición de la familia entre las visibles (1, 2, 3…). Se calcula, no se edita. */
    public function posicion(): int
    {
        return static::query()->activos()->where(
            fn ($q) => $q->where('orden', '<', $this->orden)->orWhere(fn ($q) => $q->where('orden', $this->orden)->where('id', '<', $this->id))
        )->count() + 1;
    }

    /** Rótulo «01 · 7 líneas»: posición y cantidad de líneas visibles. */
    public function rotulo(): string
    {
        $lineas = $this->lineas()->where('activo', true)->count();

        return sprintf('%02d · %d %s', $this->posicion(), $lineas, $lineas === 1 ? 'línea' : 'líneas');
    }

    /** Mensaje de WhatsApp de la ficha: el propio o «Hola, quiero cotizar {nombre}.» */
    public function mensajeWhatsapp(): string
    {
        return $this->mensaje_whatsapp ?: 'Hola, quiero cotizar '.mb_strtolower($this->nombre).'.';
    }
}
