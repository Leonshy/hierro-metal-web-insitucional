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
            'familias' => Familia::paraListado(),
        ]);
    }

    public function show(string $slug): View
    {
        $familias = Familia::paraListado();
        $familia = $familias->firstWhere('slug', $slug);

        abort_if($familia === null, 404);

        return view('productos.show', [
            'familia' => $familia,
            'lineas' => $familia->lineas()->where('activo', true)->get(),
            'familias' => $familias,
        ]);
    }
}
