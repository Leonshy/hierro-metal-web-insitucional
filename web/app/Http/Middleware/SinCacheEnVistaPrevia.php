<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Una vista previa muestra un borrador sólo a quien tiene sesión del panel: nunca debe guardarse en la caché del
 * navegador ni de un intermediario para que otra persona la pueda recibir.
 */
class SinCacheEnVistaPrevia
{
    public function handle(Request $request, Closure $next): Response
    {
        $respuesta = $next($request);

        if (Page::enVistaPrevia()) {
            $respuesta->headers->set('Cache-Control', 'private, no-store, max-age=0');
        }

        return $respuesta;
    }
}
