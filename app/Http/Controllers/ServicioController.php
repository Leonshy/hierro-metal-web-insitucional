<?php

namespace App\Http\Controllers;

use App\Models\Paso;
use App\Models\Servicio;
use App\Support\Encabezado;
use Illuminate\View\View;

/** Servicios industriales: encabezado desde la página «servicios» y el resto desde los módulos del panel. */
class ServicioController extends Controller
{
    public function index(): View
    {
        return view('servicios.index', [
            'encabezado' => Encabezado::de('servicios'),
            'servicios' => Servicio::query()->activos()->ordenados()->get(),
            'pasos' => Paso::query()->activos()->ordenados()->get(),
        ]);
    }
}
