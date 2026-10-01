@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $catalogo = Catalogo::url();
    $email = Contacto::email();
@endphp
<section class="seccion">
    <div class="contenedor">
        <div class="dos-columnas">
            <div>
                <p class="rotulo">{{ sprintf('%02d', $numero) }} — Contacto</p>
                <h2 class="titulo-seccion">¿Necesitás un presupuesto?</h2>
                <p class="bajada">Cargá tu lista de materiales y te respondemos con precio y disponibilidad en el día.</p>
                <div class="botonera">
                    <a class="btn btn-amarillo" href="{{ url('/contacto') }}">Pedir cotización</a>
                    <a class="btn btn-linea" href="{{ url('/preguntas-frecuentes') }}">Preguntas frecuentes</a>
                </div>
            </div>
            <ul class="datos">
                <li><span class="etiqueta">Ventas · WhatsApp y teléfono</span><a class="grande" href="{{ Contacto::telefonoHref() }}">{{ Contacto::telefono() }}</a></li>
                @if($email)<li><span class="etiqueta">Correo</span><a href="mailto:{{ $email }}">{{ $email }}</a></li>@endif
                <li>
                    <span class="etiqueta">Dirección · {{ Contacto::ciudad() }}</span>
                    @if(Contacto::mapsUrl())
                        <a href="{{ Contacto::mapsUrl() }}" target="_blank" rel="noopener">{{ Contacto::direccionCorta() }} →</a>
                    @else
                        <span>{{ Contacto::direccionCorta() }}</span>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</section>
