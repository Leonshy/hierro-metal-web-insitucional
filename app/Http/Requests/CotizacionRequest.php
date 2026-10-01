<?php

namespace App\Http\Requests;

use App\Rules\AdjuntoPermitido;
use App\Rules\Turnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación del formulario de cotización. Los mensajes están en la voz del sitio (docs/07 §3.2).
 * El honeypot (`sitio_web`) y la marca de tiempo (`_t`) NO se validan acá: se evalúan en la acción
 * para guardar el intento como «spam» sin avisarle al robot.
 */
class CotizacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:120'],
            'empresa' => ['nullable', 'string', 'max:120'],
            'telefono' => ['required', 'string', 'max:40', 'regex:/^[0-9+()\-\s.]{6,40}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'rubro' => ['nullable', 'string', Rule::exists('rubros', 'slug')->where('activo', true)],
            'mensaje' => ['required', 'string', 'max:4000'],
            'acepto' => ['accepted'],
            'archivos' => ['nullable', 'array', 'max:'.config('sitio.cotizaciones.maximo_adjuntos')],
            'archivos.*' => [new AdjuntoPermitido],
            'origen' => ['nullable', 'string', 'max:300'],
            'utm' => ['nullable', 'array'],
            'utm.*' => ['nullable', 'string', 'max:100'],
            'cf-turnstile-response' => [new Turnstile],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Decinos tu nombre y apellido.',
            'telefono.required' => 'Dejanos un teléfono o WhatsApp para responderte.',
            'telefono.regex' => 'Revisá el teléfono: parece incompleto.',
            'email.email' => 'Revisá el correo: parece tener un error.',
            'mensaje.required' => 'Contanos qué materiales necesitás.',
            'mensaje.max' => 'El detalle es muy largo. Resumilo o adjuntá un archivo.',
            'acepto.accepted' => 'Para enviar el pedido tenés que aceptar el uso de tus datos.',
            'archivos.max' => 'Podés adjuntar hasta '.config('sitio.cotizaciones.maximo_adjuntos').' archivos.',
            'rubro.exists' => 'Elegí una de las opciones de la lista.',
        ];
    }
}
