<?php

namespace App\Jobs;

use App\Mail\CotizacionRecibida;
use App\Models\Cotizacion;
use App\Services\Cotizaciones\Destinatarios;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** Aviso por correo de una cotización nueva. Reintenta solo; si no logra salir, el pedido sigue guardado. */
class NotificarNuevaCotizacion implements ShouldQueue
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

        if (! $cotizacion || $cotizacion->esSpam() || $cotizacion->mail_enviado_at) {
            return;
        }

        $cotizacion->increment('mail_intentos');

        $destinatarios = Destinatarios::resolver();

        // Sin destinatario cargado todavía: no es un error. Queda pendiente y el comando de
        // reintento lo retoma en cuanto se configure el correo en el panel.
        if ($destinatarios === []) {
            Log::warning('Cotización sin aviso: no hay correo de notificación configurado', ['cotizacion' => $cotizacion->id]);

            return;
        }

        Mail::to($destinatarios)->send(new CotizacionRecibida($cotizacion->load('adjuntos')));

        $cotizacion->forceFill(['mail_enviado_at' => now()])->save();
    }

    public function failed(Throwable $e): void
    {
        Log::error('Cotización: falló el aviso por correo', ['cotizacion' => $this->cotizacionId, 'error' => $e->getMessage()]);
    }
}
