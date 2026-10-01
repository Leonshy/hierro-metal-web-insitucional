<x-layouts.app title="Pedí tu cotización — Hierro Metal S.R.L." description="Mandanos tu lista de materiales, el plano o el despiece y te respondemos con precio, disponibilidad y plazo de entrega en el día.">
    {{-- Marcador de posición: el formulario maquetado (diseno/alta-fidelidad/contacto.html) se construye en la Fase 4. --}}
    <main id="contenido" tabindex="-1" class="container section">
        <h1>Pedí tu cotización</h1>
        <p>Mandanos tu lista de materiales, el plano o el despiece y te respondemos con precio, disponibilidad y plazo de entrega en el día.</p>

        <form method="POST" action="{{ route('cotizaciones.store') }}" enctype="multipart/form-data" novalidate>
            @csrf
            {{-- Anti-spam: campo señuelo que una persona no ve, y marca de tiempo firmada. --}}
            <div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden">
                <label>Sitio web <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label>
            </div>
            <input type="hidden" name="_t" value="{{ \App\Services\Cotizaciones\MarcaDeTiempo::emitir() }}">
            <input type="hidden" name="origen" value="{{ old('origen', request('desde') ?: parse_url((string) url()->previous(), PHP_URL_PATH)) }}">
            @foreach (['source', 'medium', 'campaign'] as $utm)
                <input type="hidden" name="utm[{{ $utm }}]" value="{{ old('utm.'.$utm, request('utm_'.$utm)) }}">
            @endforeach

            @php($campos = ['nombre' => ['Nombre y apellido', 'text', true, 'name'], 'empresa' => ['Empresa u obra', 'text', false, 'organization'], 'telefono' => ['Teléfono o WhatsApp', 'tel', true, 'tel'], 'email' => ['Correo electrónico', 'email', false, 'email']])
            @foreach ($campos as $nombre => [$etiqueta, $tipo, $requerido, $autocompletar])
                <div class="campo">
                    <label for="{{ $nombre }}">{{ $etiqueta }}@if($requerido) <span aria-hidden="true">*</span>@endif</label>
                    <input id="{{ $nombre }}" name="{{ $nombre }}" type="{{ $tipo }}" value="{{ old($nombre) }}" autocomplete="{{ $autocompletar }}" @required($requerido)>
                    @error($nombre)<span class="mensaje-error">{{ $message }}</span>@enderror
                </div>
            @endforeach

            <div class="campo">
                <label for="rubro">¿Qué necesitás?</label>
                <select id="rubro" name="rubro">
                    <option value="">Elegí una opción</option>
                    @foreach ($rubros as $rubro)
                        <option value="{{ $rubro->slug }}" @selected(old('rubro', request('rubro')) === $rubro->slug)>{{ $rubro->nombre }}</option>
                    @endforeach
                </select>
                @error('rubro')<span class="mensaje-error">{{ $message }}</span>@enderror
            </div>

            <div class="campo">
                <label for="mensaje">Lista de materiales o detalle del pedido <span aria-hidden="true">*</span></label>
                <textarea id="mensaje" name="mensaje" rows="8" required placeholder="Ejemplo:&#10;- 20 chapas trapezoidales galvanizadas, largo 6 m&#10;- 10 perfiles UPN 100, 12 m&#10;- Corte a medida según plano&#10;- Entrega en obra, zona Luque">{{ old('mensaje') }}</textarea>
                <span class="ayuda">Cuanto más detalle nos des (medidas, espesores, cantidades, zona de entrega), más rápido te cotizamos.</span>
                @error('mensaje')<span class="mensaje-error">{{ $message }}</span>@enderror
            </div>

            <div class="campo">
                <label for="archivos">Plano, despiece o lista (opcional)</label>
                <input id="archivos" name="archivos[]" type="file" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,.dwg,.dxf,.xlsx">
                <span class="ayuda">PDF, imagen, DWG, DXF o Excel. Hasta {{ config('sitio.cotizaciones.maximo_adjuntos') }} archivos de 10 MB cada uno.</span>
                @error('archivos')<span class="mensaje-error">{{ $message }}</span>@enderror
                @foreach ($errors->get('archivos.*') as $mensajes)
                    @foreach ($mensajes as $mensaje)<span class="mensaje-error">{{ $mensaje }}</span>@endforeach
                @endforeach
            </div>

            <x-turnstile-widget />

            <label class="consentimiento">
                <input type="checkbox" name="acepto" value="1" @checked(old('acepto')) required>
                <span>Al enviar, aceptás que usemos tus datos para responder esta consulta. Ver la <a href="{{ url('/privacidad') }}">política de privacidad</a>.</span>
            </label>
            @error('acepto')<span class="mensaje-error">{{ $message }}</span>@enderror

            <button class="btn btn-primary" type="submit">Enviar pedido</button>
        </form>
    </main>
</x-layouts.app>
