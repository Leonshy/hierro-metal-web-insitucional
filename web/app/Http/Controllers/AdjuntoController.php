<?php

namespace App\Http\Controllers;

use App\Models\CotizacionAdjunto;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Descarga de adjuntos de cotización: sólo con sesión del panel y permiso, siempre como descarga. */
class AdjuntoController extends Controller
{
    public function descargar(CotizacionAdjunto $adjunto): StreamedResponse
    {
        $usuario = auth()->user();

        // Sin sesión no se revela ni que el archivo exista.
        abort_unless($usuario !== null, 404);
        abort_unless($usuario->is_active && $usuario->can('cotizaciones.view'), 403);
        abort_unless(Storage::disk($adjunto->disco)->exists($adjunto->ruta), 404);

        return Storage::disk($adjunto->disco)->download($adjunto->ruta, $adjunto->nombre_original, [
            // Se descarga, no se abre en el navegador: nunca se renderiza contenido de terceros.
            'Content-Type' => 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
