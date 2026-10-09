<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use App\Models\Page;
use App\Support\Encabezado;
use Illuminate\View\View;

/** Novedades (blog): listado y nota. Sin ninguna novedad visible, la sección entera no existe para el público. */
class NovedadController extends Controller
{
    public function index(): View
    {
        abort_unless(Page::visible('novedades'), 404);

        return view('novedades.index', [
            'encabezado' => Encabezado::de('novedades'),
            'novedades' => Novedad::query()->with('media')->publicadas()->recientes()->paginate(9),
        ]);
    }

    public function show(string $slug): View
    {
        $novedad = Novedad::query()->with('media')->where('slug', $slug)->firstOrFail();

        // Una nota sin publicar (borrador o programada) sólo la ve, en vista previa, quien tiene sesión del panel.
        if (! $novedad->estaPublicada()) {
            abort_unless(Page::puedeVerBorradores(), 404);
            Page::marcarVistaPrevia();
        } else {
            abort_unless(Page::visible('novedades'), 404);
        }

        return view('novedades.show', [
            'novedad' => $novedad,
            'otras' => Novedad::query()->with('media')->publicadas()->whereKeyNot($novedad->id)->recientes()->limit(3)->get(),
        ]);
    }
}
