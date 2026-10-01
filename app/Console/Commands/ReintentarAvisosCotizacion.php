<?php

namespace App\Console\Commands;

use App\Jobs\NotificarNuevaCotizacion;
use App\Models\Cotizacion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Retoma los avisos de cotización que no salieron (SMTP caído, correo sin configurar).
 * Si un pedido sigue sin aviso tras varios intentos, alerta a webparaguay una sola vez.
 */
class ReintentarAvisosCotizacion extends Command
{
    protected $signature = 'cotizaciones:reintentar-avisos';

    protected $description = 'Reintenta los avisos por correo de cotizaciones que quedaron sin enviar';

    public function handle(): int
    {
        $limite = now()->subMinutes((int) config('sitio.cotizaciones.reintento_minutos'));
        $pendientes = Cotizacion::query()->conAvisoPendiente()->where('created_at', '<=', $limite)->get();

        foreach ($pendientes as $cotizacion) {
            NotificarNuevaCotizacion::dispatch($cotizacion->id);

            if ($cotizacion->mail_intentos >= (int) config('sitio.cotizaciones.alerta_tras_intentos') && ! $cotizacion->alertada_at) {
                $this->alertar($cotizacion);
            }
        }

        $this->info("Avisos reintentados: {$pendientes->count()}.");

        return self::SUCCESS;
    }

    private function alertar(Cotizacion $cotizacion): void
    {
        Log::critical('Cotización sin aviso tras varios intentos: revisar el SMTP y el correo de notificación', [
            'cotizacion' => $cotizacion->id,
            'intentos' => $cotizacion->mail_intentos,
        ]);

        if ($destino = config('sitio.cotizaciones.email_alerta')) {
            try {
                Mail::raw("La cotización #{$cotizacion->id} lleva {$cotizacion->mail_intentos} intentos sin enviarse. El pedido está guardado en el panel.", fn ($m) => $m->to($destino)->subject('Hierro Metal: aviso de cotización sin enviar'));
            } catch (Throwable $e) {
                Log::error('No se pudo enviar la alerta de aviso pendiente', ['error' => $e->getMessage()]);
            }
        }

        $cotizacion->forceFill(['alertada_at' => now()])->save();
    }
}
