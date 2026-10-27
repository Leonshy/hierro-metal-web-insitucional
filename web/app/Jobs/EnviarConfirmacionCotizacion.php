<?php

namespace App\Jobs;

use App\Mail\CotizacionConfirmada;
use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Correo de «pedido recibido» a quien completó el formulario. Va aparte del aviso interno para que un fallo de uno
 * no afecte al otro; sólo se manda si dejó un correo válido, nunca por spam y una sola vez por pedido.
 */
class EnviarConfirmacionCotizacion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(public int $cotizacionId) {}

    /** @return array<int, int> */
    public function backoff(): array
    {
        return [60, 300, 900, 3600];
    }

    public function handle(): void
    {
        $cotizacion = Cotizacion::query()->find($this->cotizacionId);

        if (! $cotizacion || $cotizacion->esSpam() || $cotizacion->confirmacion_enviada_at) {
            return;
        }

        if (! filter_var($cotizacion->email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        Mail::to($cotizacion->email)->send(new CotizacionConfirmada($cotizacion));

        $cotizacion->forceFill(['confirmacion_enviada_at' => now()])->save();
    }

    public function failed(Throwable $e): void
    {
        Log::error('Cotización: falló el correo de pedido recibido', ['cotizacion' => $this->cotizacionId, 'error' => $e->getMessage()]);
    }
}
