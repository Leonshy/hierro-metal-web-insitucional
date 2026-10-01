<?php

namespace App\Http\Controllers;

use App\Models\Diferencial;
use App\Models\Familia;
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
            'secciones' => SeccionInicio::query()->activos()->ordenados()->get(),
            'servicios' => Servicio::query()->activos()->where('destacado_home', true)->ordenados()->get(),
        ]);
    }
}
