@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $catalogo = Catalogo::url();
    $email = Contacto::email();
    $aviso = Contacto::avisoNumeroUnico();
    $maximo = config('sitio.cotizaciones.maximo_adjuntos');
    $rubroElegido = old('rubro', request('rubro'));
@endphp
<x-layouts.app title="Pedí tu cotización · Hierro Metal S.R.L." description="Mandanos tu lista de materiales, el plano o el despiece y te respondemos con precio, disponibilidad y plazo de entrega en el día.">
    <main id="contenido" tabindex="-1">
        <section class="encabezado-pagina amarilla">
            <div class="contenedor">
                <h1>Pedí tu cotización</h1>
                <p class="bajada">Mandanos tu lista de materiales, el plano o el despiece y te respondemos con precio, disponibilidad y plazo de entrega en el día. Si preferís hablar, escribinos por WhatsApp o llamanos.</p>
            </div>
        </section>

        <section class="seccion">
            <div class="contenedor">
                <div class="dos-columnas dos-columnas-contacto">
                    <div>
                        <h2 class="titulo-seccion titulo-familia">Contanos qué necesitás</h2>

                        @if($errors->any())
                            <div class="aviso aviso-error" data-tipo="Revisá" role="alert" tabindex="-1" id="errores-formulario">
                                Hay datos para corregir. No se perdió nada de lo que escribiste.
                            </div>
                        @endif

                        <form class="formulario" id="formulario-cotizacion" action="{{ route('cotizaciones.store') }}" method="post" enctype="multipart/form-data" novalidate>
                            @csrf
                            {{-- Anti-spam: campo señuelo que una persona no ve, y marca de tiempo firmada. --}}
                            <div class="trampa" aria-hidden="true"><label>Sitio web <input type="text" name="sitio_web" tabindex="-1" autocomplete="off"></label></div>
                            <input type="hidden" name="_t" value="{{ \App\Services\Cotizaciones\MarcaDeTiempo::emitir() }}">
                            <input type="hidden" name="origen" value="{{ old('origen', request('desde') ?: parse_url((string) url()->previous(), PHP_URL_PATH)) }}">
                            @foreach(['source', 'medium', 'campaign'] as $utm)
                                <input type="hidden" name="utm[{{ $utm }}]" value="{{ old('utm.'.$utm, request('utm_'.$utm)) }}">
                            @endforeach

                            <div class="campo">
                                <label for="nombre">Nombre y apellido <span class="req" aria-hidden="true">*</span></label>
                                <input id="nombre" name="nombre" type="text" autocomplete="name" maxlength="120" required aria-required="true" value="{{ old('nombre') }}" @error('nombre') aria-invalid="true" aria-describedby="nombre-error" @enderror>
                                @error('nombre')<span class="mensaje-error" id="nombre-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="campo">
                                <label for="empresa">Empresa u obra</label>
                                <input id="empresa" name="empresa" type="text" maxlength="120" value="{{ old('empresa') }}" @error('empresa') aria-invalid="true" aria-describedby="empresa-error" @enderror>
                                @error('empresa')<span class="mensaje-error" id="empresa-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="campo">
                                <label for="telefono">Teléfono o WhatsApp <span class="req" aria-hidden="true">*</span></label>
                                <input id="telefono" name="telefono" type="tel" inputmode="tel" autocomplete="tel" placeholder="09xx xxx xxx" maxlength="40" required aria-required="true" value="{{ old('telefono') }}" @error('telefono') aria-invalid="true" aria-describedby="telefono-error" @enderror>
                                @error('telefono')<span class="mensaje-error" id="telefono-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="campo">
                                <label for="email">Correo electrónico</label>
                                <input id="email" name="email" type="email" autocomplete="email" maxlength="150" value="{{ old('email') }}" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                                @error('email')<span class="mensaje-error" id="email-error">{{ $message }}</span>@else<span class="ayuda">Opcional, pero nos ayuda a mandarte la cotización por escrito.</span>@enderror
                            </div>

                            <div class="campo">
                                <label for="rubro">¿Qué necesitás?</label>
                                <select id="rubro" name="rubro" @error('rubro') aria-invalid="true" aria-describedby="rubro-error" @enderror>
                                    <option value="">Elegí una opción</option>
                                    @foreach($rubros as $rubro)
                                        <option value="{{ $rubro->slug }}" @selected($rubroElegido === $rubro->slug)>{{ $rubro->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('rubro')<span class="mensaje-error" id="rubro-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="campo">
                                <label for="mensaje">Lista de materiales o detalle del pedido <span class="req" aria-hidden="true">*</span></label>
                                <textarea id="mensaje" name="mensaje" maxlength="4000" required aria-required="true" placeholder="Ejemplo:&#10;- 20 chapas trapezoidales galvanizadas, largo 6 m&#10;- 10 perfiles UPN 100, 12 m&#10;- Corte a medida según plano&#10;- Entrega en obra, zona Luque" @error('mensaje') aria-invalid="true" aria-describedby="mensaje-error" @enderror>{{ old('mensaje') }}</textarea>
                                @error('mensaje')<span class="mensaje-error" id="mensaje-error">{{ $message }}</span>@else<span class="ayuda">Cuanto más detalle nos des (medidas, espesores, cantidades, zona de entrega), más rápido te cotizamos.</span>@enderror
                            </div>

                            <div class="campo campo-archivo">
                                <label for="archivos">Plano, despiece o lista (opcional)</label>
                                <label class="archivo-zona" for="archivos" data-archivos-zona><svg class="icono-mas" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg><span data-archivos-texto>Elegir archivos</span></label>
                                <input id="archivos" name="archivos[]" type="file" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,.dwg,.dxf,.xlsx" class="solo-lectores" data-maximo="{{ $maximo }}">
                                <span class="ayuda">PDF, imagen, DWG, DXF o Excel. Hasta {{ $maximo }} archivos de 10 MB cada uno.</span>
                                @error('archivos')<span class="mensaje-error">{{ $message }}</span>@enderror
                                @foreach($errors->get('archivos.*') as $mensajes)
                                    @foreach($mensajes as $mensaje)<span class="mensaje-error">{{ $mensaje }}</span>@endforeach
                                @endforeach
                            </div>

                            <x-turnstile-widget />

                            <label class="consentimiento">
                                <input type="checkbox" name="acepto" value="1" required aria-required="true" @checked(old('acepto'))>
                                <span>Al enviar, aceptás que usemos tus datos para responder esta consulta. Ver la <a href="{{ url('/privacidad') }}">política de privacidad</a>.</span>
                            </label>
                            @error('acepto')<span class="mensaje-error">{{ $message }}</span>@enderror

                            <button class="btn btn-amarillo" type="submit" data-enviando="Enviando tu pedido…">Enviar pedido</button>
                        </form>
                    </div>

                    <aside aria-label="Canales directos">
                        <h2 class="titulo-seccion titulo-familia">O escribinos ahora</h2>
                        <ul class="datos">
                            <li><span class="etiqueta">Ventas · WhatsApp y teléfono</span><a class="grande" href="{{ Contacto::telefonoHref() }}">{{ Contacto::telefono() }}</a></li>
                            @if($email)<li><span class="etiqueta">Correo</span><a href="mailto:{{ $email }}">{{ $email }}</a></li>@endif
                            <li>
                                <span class="etiqueta">Depósito y taller</span>
                                @if(Contacto::mapsUrl())
                                    <a href="{{ Contacto::mapsUrl() }}" target="_blank" rel="noopener">{{ Contacto::direccionCorta() }}, {{ Contacto::ciudad() }} →</a>
                                @else
                                    <span>{{ Contacto::direccionCorta() }}, {{ Contacto::ciudad() }}</span>
                                @endif
                            </li>
                            @if($horarios->isNotEmpty())
                                <li>
                                    <span class="etiqueta">Horario de atención</span>
                                    <p><span class="estado-horario {{ $estadoHorario['abierto'] ? 'estado-abierto' : 'estado-cerrado' }}">{{ $estadoHorario['texto'] }}</span></p>
                                    <ul class="horarios">
                                        @foreach($horarios as $h)
                                            <li><span>{{ $h->etiqueta }}</span><span>{{ $h->cerrado ? 'Cerrado' : substr($h->abre, 0, 5).' — '.substr($h->cierra, 0, 5) }}</span></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        </ul>
                        <div class="botonera">
                            <a class="btn btn-negro" href="{{ Contacto::whatsappUrl('Hola, quiero pedir una cotización.') }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                            @if($catalogo)<a class="btn btn-linea" href="{{ $catalogo }}">↓ {{ Catalogo::textoBoton() }}</a>@endif
                        </div>
                        @if($aviso)
                            <div class="aviso" data-tipo="Aviso">{{ $aviso }}</div>
                        @endif
                    </aside>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
