<?php

namespace App\Http\Controllers;

use App\Models\Diferencial;
use App\Models\Familia;
use App\Models\Page;
use App\Models\SeccionInicio;
use App\Models\Servicio;
use App\Support\Encabezado;
use Illuminate\View\View;

/** Portada: textos de encabezado desde la página «inicio» y el resto desde los módulos del panel. */
class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'encabezado' => Encabezado::de('inicio'),
            'diferenciales' => Diferencial::query()->activos()->ordenados()->get(),
            'familias' => Familia::paraListado(),
            // Una sección cuya página está en borrador no se muestra en la portada (llevaría a una página que no existe).
            'secciones' => SeccionInicio::query()->activos()->ordenados()->get()->filter(fn (SeccionInicio $seccion): bool => Page::seccionPublicada($seccion->clave))->values(),
            'servicios' => Servicio::query()->activos()->where('destacado_home', true)->ordenados()->get(),
        ]);
    }
}
