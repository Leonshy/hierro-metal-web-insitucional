<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Support\Encabezado;
use Illuminate\View\View;

/** Catálogo público: listado de familias y ficha de cada una con sus líneas y medidas. */
class ProductoController extends Controller
{
    public function index(): View
    {
        return view('productos.index', [
            'encabezado' => Encabezado::de('productos'),
            'familias' => Familia::query()->with('media')->activos()->ordenados()->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $familia = Familia::query()->with('media')->activos()->where('slug', $slug)->firstOrFail();

        return view('productos.show', [
            'familia' => $familia,
            'lineas' => $familia->lineas()->where('activo', true)->get(),
            'familias' => Familia::query()->activos()->ordenados()->get(),
        ]);
    }
}
