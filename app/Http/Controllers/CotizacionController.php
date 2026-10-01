<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotizacionRequest;
use App\Models\Rubro;
use App\Services\Cotizaciones\GuardarCotizacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Controlador fino: valida, delega en la acción y redirige a la página de gracias. */
class CotizacionController extends Controller
{
    public function create(): View
    {
        return view('contact.show', ['rubros' => Rubro::query()->activos()->ordenados()->get()]);
    }

    public function store(CotizacionRequest $request, GuardarCotizacion $accion): RedirectResponse
    {
        $archivos = $request->file('archivos', []);

        // Un intento de spam recibe la misma respuesta que un pedido real: el robot no se entera.
        $resultado = $accion->handle($request->validated(), is_array($archivos) ? $archivos : [$archivos], $request);

        return redirect()->route('cotizaciones.gracias')->with('meta_event_id', $resultado['meta_event_id']);
    }

    public function gracias(): View
    {
        return view('contact.gracias');
    }
}
