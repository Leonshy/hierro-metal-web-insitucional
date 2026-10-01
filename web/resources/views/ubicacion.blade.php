@php
    use App\Support\Contacto;
    $titulo = $encabezado->titulo ?: 'Casa matriz en Fernando de la Mora';
    $mensaje = 'Hola, quiero consultar cómo llegar al depósito.';
    $email = Contacto::email();
    $mapa = Contacto::mapsUrl();
    $instagram = Contacto::instagram();
    $facebook = Contacto::facebook();
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo" :description="$encabezado->seoDescripcion" :whatsapp="$mensaje">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$titulo" rotulo="Ubicación" :bajada="$encabezado->bajada" :migas="[['Inicio', '/'], ['Ubicación', null]]">
            @if($mapa)<a class="btn btn-amarillo" href="{{ $mapa }}" target="_blank" rel="noopener">Cómo llegar</a>@endif
            <a class="btn btn-linea" href="{{ Contacto::telefonoHref() }}">Llamar antes de venir</a>
        </x-encabezado-pagina>

        <section class="seccion">
            <div class="contenedor">
                <div class="dos-columnas">
                    <div>
                        <p class="rotulo">Dirección</p>
                        <h2 class="titulo-seccion titulo-familia">{{ Contacto::direccionCorta() }}</h2>
                        <p class="bajada">{{ Contacto::direccionLarga() }}.</p>
                        @if($horarios->isNotEmpty())
                            <p class="rotulo" style="margin-top:34px">Horario de atención</p>
                            <p><span class="estado-horario {{ $estadoHorario['abierto'] ? 'estado-abierto' : 'estado-cerrado' }}">{{ $estadoHorario['texto'] }}</span></p>
                            <ul class="horarios">
                                @foreach($horarios as $h)
                                    <li><span>{{ $h->etiqueta }}</span><span>{{ $h->cerrado ? 'Cerrado' : substr($h->abre, 0, 5).' — '.substr($h->cierra, 0, 5) }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <ul class="datos">
                        <li><span class="etiqueta">Ventas · WhatsApp y teléfono</span><a class="grande" href="{{ Contacto::telefonoHref() }}">{{ Contacto::telefono() }}</a></li>
                        @if($email)<li><span class="etiqueta">Correo</span><a href="mailto:{{ $email }}">{{ $email }}</a></li>@endif
                        @if($instagram)<li><span class="etiqueta">Instagram</span><a href="{{ $instagram }}" target="_blank" rel="noopener">Seguinos →</a></li>@endif
                        @if($facebook)<li><span class="etiqueta">Facebook</span><a href="{{ $facebook }}" target="_blank" rel="noopener">Seguinos →</a></li>@endif
                    </ul>
                </div>
            </div>
        </section>

        <section class="seccion-chica alterna">
            <div class="contenedor">
                <p class="rotulo">Mapa</p>
                <h2 class="titulo-seccion" style="margin-bottom:26px">Dónde estamos</h2>
                <x-mapa-fachada />
            </div>
        </section>

        <section class="seccion-chica amarilla">
            <div class="contenedor">
                <h2 class="titulo-seccion">¿Preferís que lo llevemos nosotros?</h2>
                <p class="bajada">Tenemos flota propia de camiones y entregamos en obra en todo Paraguay.</p>
                <div class="botonera"><a class="btn btn-negro" href="{{ url('/contacto') }}">Pedir cotización</a></div>
            </div>
        </section>
    </main>
</x-layouts.app>
