<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotizacionRequest;
use App\Models\Horario;
use App\Models\Paso;
use App\Models\Rubro;
use App\Services\Cotizaciones\GuardarCotizacion;
use App\Support\Encabezado;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Controlador fino: valida, delega en la acción y redirige a la página de gracias. */
class CotizacionController extends Controller
{
    public function create(): View
    {
        $horarios = Horario::query()->activos()->ordenados()->get();

        return view('contact.show', [
            'encabezado' => Encabezado::de('contacto'),
            'rubros' => Rubro::query()->activos()->ordenados()->get(),
            'horarios' => $horarios,
            'estadoHorario' => Horario::estadoAhora(null, $horarios),
        ]);
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
        // El primer paso del cliente es «Nos mandás el pedido»: ya ocurrió, así que se muestran los que siguen.
        return view('contact.gracias', ['pasos' => Paso::query()->activos()->ordenados()->get()->slice(1)->values()]);
    }
}
