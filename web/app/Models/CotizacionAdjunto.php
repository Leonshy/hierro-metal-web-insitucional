<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Archivo adjunto a una cotización (plano, despiece). Vive en disco privado, nunca en `public/`.
 *
 * @property string $nombre_original
 * @property int $tamano
 */
#[Fillable(['cotizacion_id', 'disco', 'ruta', 'nombre_original', 'mime', 'tamano'])]
class CotizacionAdjunto extends Model
{
    protected $table = 'cotizacion_adjuntos';

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function tamanoLegible(): string
    {
        return $this->tamano >= 1048576
            ? number_format($this->tamano / 1048576, 1, ',', '.').' MB'
            : max(1, (int) round($this->tamano / 1024)).' KB';
    }
}
