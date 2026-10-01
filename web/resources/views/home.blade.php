@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    use Illuminate\Support\Facades\Storage;
    $catalogo = Catalogo::url();
    $email = Contacto::email();
    $titulo = $encabezado->titulo ?: 'Hierro Metal S.R.L.';
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo" :description="$encabezado->seoDescripcion">
    <main id="contenido" tabindex="-1">
        <section class="oscura">
            <div class="contenedor">
                <div class="portada-texto">
                    <span class="insignia">Importación y venta · Mayorista y minorista</span>
                    <h1>{{ $titulo }}</h1>
                    @if($encabezado->bajada)<p class="bajada">{{ $encabezado->bajada }}</p>@endif
                    <div class="botonera">
                        <a class="btn btn-amarillo" href="{{ url($encabezado->ctaUrl ?: '/contacto') }}">{{ $encabezado->ctaTexto ?: 'Pedir cotización' }}</a>
                        <a class="btn btn-linea" href="{{ url('/productos') }}">Ver productos y medidas</a>
                        @if($catalogo)<a class="btn btn-linea" href="{{ $catalogo }}">↓ {{ Catalogo::textoBoton() }}</a>@endif
                    </div>
                    <p class="nota-portada">Todas las chapas con certificado de calidad del fabricante</p>
                </div>
            </div>
            @if($encabezado->imagen)
                {{-- Foto del catálogo, editable desde el panel (página «inicio», bloque hero). Decorativa: el texto ya dice qué se vende. --}}
                <img class="ilustracion ilustracion-portada foto-portada" src="{{ Storage::disk('public')->url($encabezado->imagen) }}" alt="" width="1132" height="859" fetchpriority="high">
            @else
                <x-ilustracion nombre="portada" class="ilustracion ilustracion-portada" />
            @endif
        </section>

        @if($diferenciales->isNotEmpty())
            <section class="amarilla" aria-label="Diferenciales">
                <ul class="diferenciales">
                    @foreach($diferenciales as $d)
                        <li><strong>{{ $d->titulo }}</strong><span>{{ $d->texto }}</span></li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section class="seccion">
            <div class="contenedor">
                <p class="rotulo">01 — Productos</p>
                <h2 class="titulo-seccion">Qué comercializamos</h2>
                <p class="bajada">Cinco familias de materiales metálicos y metalúrgicos con stock permanente. Entrá al catálogo para ver espesores, medidas y normas de cada línea.</p>
                <ul class="rejilla">
                    @foreach($familias as $familia)
                        <li>
                            <x-familia-tarjeta :familia="$familia" />
                        </li>
                    @endforeach
                    @if($catalogo)
                        <li class="ficha-destacada">
                            <div class="caja">
                                <span class="ficha-indice">Catálogo</span>
                                <span class="ficha-titulo">Catálogo de materiales</span>
                                <span class="ficha-texto">Nuestro catálogo general en PDF. Confirmá medidas, espesores y stock actual por WhatsApp antes de comprar.</span>
                                <a class="btn btn-amarillo" href="{{ $catalogo }}">↓ Descargar PDF</a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </section>

        <section class="seccion oscura">
            <div class="contenedor">
                <p class="rotulo">02 — Servicios industriales</p>
                <h2 class="titulo-seccion">Trabajamos el material por vos</h2>
                <p class="bajada">Cortes sobre medida, plegados, perforaciones, fabricación especial de perfiles C y U, galvanización y entrega en obra con flota propia.</p>
                @if($servicios->isNotEmpty())
                    <ul class="rejilla-oscura">
                        @foreach($servicios as $s)
                            <li><strong>{{ $s->titulo_home ?: $s->nombre }}</strong><span>{{ $s->resumen_home }}</span></li>
                        @endforeach
                    </ul>
                @endif
                <div class="botonera"><a class="btn btn-amarillo" href="{{ url('/servicios') }}">Ver los servicios</a></div>
            </div>
        </section>

        <section class="seccion-chica amarilla">
            <div class="contenedor">
                <p class="rotulo">03 — Política de calidad</p>
                <h2 class="titulo-seccion">Materia prima certificada bajo Normas Internacionales del Acero</h2>
                <p class="bajada">Control de calidad estricto, infraestructura mantenida y mejora continua de productos y procesos.</p>
                <div class="botonera"><a class="btn btn-negro" href="{{ url('/calidad') }}">Leer la política completa</a></div>
            </div>
        </section>

        <section class="seccion">
            <div class="contenedor">
                <div class="dos-columnas">
                    <div>
                        <p class="rotulo">04 — Contacto</p>
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
    </main>
</x-layouts.app>
