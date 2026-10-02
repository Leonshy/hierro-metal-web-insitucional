<?php

namespace App\Services\Cotizaciones;

use App\Jobs\NotificarNuevaCotizacion;
use App\Models\Cotizacion;
use App\Models\CotizacionAdjunto;
use App\Models\Rubro;
use App\Services\Integrations\MetaConversionsApi;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Guarda el pedido de cotización. **El orden es la regla**: primero se persiste el pedido (y sus
 * adjuntos), después se intenta el aviso por correo. Si el SMTP está caído, el pedido ya está en la base.
 */
class GuardarCotizacion
{
    public function __construct(private readonly MetaConversionsApi $meta) {}

    /**
     * @param  array<string, mixed>  $datos  datos ya validados
     * @param  array<int, UploadedFile>  $archivos
     * @return array{cotizacion: Cotizacion, spam: bool, meta_event_id: ?string}
     */
    public function handle(array $datos, array $archivos, Request $request): array
    {
        $spam = $this->esSpam($request);

        $cotizacion = Cotizacion::query()->create([
            'nombre' => $datos['nombre'],
            'empresa' => $datos['empresa'] ?? null,
            'ci_ruc' => $datos['ci_ruc'],
            'telefono' => $datos['telefono'],
            'email' => $datos['email'] ?? null,
            'rubro' => filled($datos['rubro'] ?? null) ? Rubro::query()->where('slug', $datos['rubro'])->value('nombre') : null,
            'mensaje' => $datos['mensaje'],
            'origen' => $this->origen($datos, $request),
            'utm' => $this->utm($datos),
            'estado' => $spam ? 'spam' : 'nueva',
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 300, ''),
        ]);

        // Un intento de spam se guarda para medirlo, pero sin archivos, sin aviso y sin Meta.
        if ($spam) {
            return ['cotizacion' => $cotizacion, 'spam' => true, 'meta_event_id' => null];
        }

        $this->guardarAdjuntos($cotizacion, $archivos);
        $this->avisar($cotizacion);

        return ['cotizacion' => $cotizacion, 'spam' => false, 'meta_event_id' => $this->avisarAMeta($cotizacion, $request)];
    }

    /** Honeypot lleno, o marca de tiempo ausente, falsificada o demasiado reciente. */
    private function esSpam(Request $request): bool
    {
        if (filled($request->input('sitio_web'))) {
            return true;
        }

        $edad = MarcaDeTiempo::edadEnSegundos($request->input('_t'));

        return $edad === null || $edad < (int) config('sitio.cotizaciones.segundos_minimos');
    }

    /** Página desde la que se envió: sólo rutas internas, para no guardar enlaces externos. */
    private function origen(array $datos, Request $request): ?string
    {
        $candidato = $datos['origen'] ?? parse_url((string) $request->headers->get('referer'), PHP_URL_PATH);

        return is_string($candidato) && str_starts_with($candidato, '/') && ! str_starts_with($candidato, '//')
            ? Str::limit($candidato, 300, '')
            : null;
    }

    /** @return array<string, string>|null */
    private function utm(array $datos): ?array
    {
        $utm = array_filter(
            array_intersect_key((array) ($datos['utm'] ?? []), array_flip(['source', 'medium', 'campaign'])),
            fn ($valor) => filled($valor),
        );

        return $utm === [] ? null : $utm;
    }

    /** @param  array<int, UploadedFile>  $archivos */
    private function guardarAdjuntos(Cotizacion $cotizacion, array $archivos): void
    {
        $disco = config('sitio.cotizaciones.disco_adjuntos');

        foreach ($archivos as $archivo) {
            try {
                $extension = strtolower($archivo->getClientOriginalExtension());
                $ruta = Storage::disk($disco)->putFileAs(
                    "cotizaciones/{$cotizacion->uuid}",
                    $archivo,
                    Str::uuid().'.'.$extension,
                );

                CotizacionAdjunto::query()->create([
                    'cotizacion_id' => $cotizacion->id,
                    'disco' => $disco,
                    'ruta' => $ruta,
                    'nombre_original' => $this->nombreSeguro($archivo->getClientOriginalName()),
                    'mime' => (string) $archivo->getMimeType(),
                    'tamano' => (int) $archivo->getSize(),
                ]);
            } catch (Throwable $e) {
                // Un adjunto que falla no puede costar el pedido: queda anotado para Ventas.
                Log::error('Cotización: no se pudo guardar un adjunto', ['cotizacion' => $cotizacion->id, 'error' => $e->getMessage()]);
                $cotizacion->update(['notas_internas' => trim($cotizacion->notas_internas."\nNo se pudo guardar el adjunto «".$archivo->getClientOriginalName().'».')]);
            }
        }
    }

    /** Sin rutas ni caracteres de control: el nombre original sólo se muestra, nunca se usa para guardar. */
    private function nombreSeguro(string $nombre): string
    {
        $limpio = preg_replace('/[^\p{L}\p{N}._ \-()]/u', '_', basename(str_replace('\\', '/', $nombre))) ?? 'archivo';

        return Str::limit($limpio, 120, '');
    }

    /** El aviso va en cola; si la cola o el SMTP fallan, el pedido ya está guardado y se reintenta. */
    private function avisar(Cotizacion $cotizacion): void
    {
        try {
            NotificarNuevaCotizacion::dispatch($cotizacion->id);
        } catch (Throwable $e) {
            Log::error('Cotización: no se pudo enviar el aviso (queda pendiente de reintento)', [
                'cotizacion' => $cotizacion->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Meta recibe el evento sólo si la persona aceptó las cookies de publicidad. Por defecto
     * no se le manda ni el teléfono ni el correo (`sitio.cotizaciones.meta_enviar_datos_personales`).
     */
    private function avisarAMeta(Cotizacion $cotizacion, Request $request): ?string
    {
        if ($request->cookie('sitio_consent_marketing') !== '1') {
            return null;
        }

        $datos = config('sitio.cotizaciones.meta_enviar_datos_personales')
            ? ['email' => $cotizacion->email, 'phone' => $cotizacion->telefono]
            : [];

        try {
            return $this->meta->send('Lead', $datos, $request);
        } catch (Throwable $e) {
            Log::warning('Meta: no se pudo registrar el evento', ['error' => $e->getMessage()]);

            return null;
        }
    }
}
