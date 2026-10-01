<?php

namespace App\Http\Controllers;

use App\Support\Catalogo;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Descarga pública del catálogo vigente. El enlace no cambia cuando el cliente reemplaza el archivo. */
class CatalogoController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        $ruta = Catalogo::ruta();

        abort_if($ruta === null, 404);

        return Storage::disk(Catalogo::DISCO)->download($ruta, 'catalogo-hierro-metal.pdf', [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            // Se revalida siempre: al reemplazar el PDF nadie debe seguir viendo el anterior.
            'Cache-Control' => 'public, no-cache',
        ]);
    }
}
