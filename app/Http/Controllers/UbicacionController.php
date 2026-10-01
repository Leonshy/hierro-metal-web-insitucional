<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Support\Encabezado;
use Illuminate\View\View;

class UbicacionController extends Controller
{
    public function index(): View
    {
        $horarios = Horario::query()->activos()->ordenados()->get();

        return view('ubicacion', [
            'encabezado' => Encabezado::de('ubicacion'),
            'horarios' => $horarios,
            'estadoHorario' => Horario::estadoAhora(null, $horarios),
        ]);
    }
}
