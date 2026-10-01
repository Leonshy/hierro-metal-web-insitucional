<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Page;
use App\Support\Encabezado;
use Illuminate\View\View;

class UbicacionController extends Controller
{
    public function index(): View
    {
        abort_unless(Page::seccionPublicada('ubicacion'), 404);

        $horarios = Horario::query()->activos()->ordenados()->get();

        return view('ubicacion', [
            'encabezado' => Encabezado::de('ubicacion'),
            'horarios' => $horarios,
            'estadoHorario' => Horario::estadoAhora(null, $horarios),
        ]);
    }
}
