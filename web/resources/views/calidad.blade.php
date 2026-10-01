@php
    use App\Support\Contacto;
    $titulo = $encabezado->titulo ?: 'Política de calidad';
    $mensaje = 'Hola, quiero consultar por la calidad y los certificados del material.';
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo" :description="$encabezado->seoDescripcion" :indexable="$pagina->is_indexable" :whatsapp="$mensaje">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$titulo" :bajada="$encabezado->bajada" clima="amarilla" :migas="[['Inicio', '/'], ['Calidad', null]]" />

        @if(trim(strip_tags($introduccion)) !== '')
            <section class="seccion-chica">
                <div class="contenedor"><div class="prosa">{!! $introduccion !!}</div></div>
            </section>
        @endif

        @if($compromisos->isNotEmpty())
            <section class="seccion-chica alterna">
                <div class="contenedor">
                    <h2 class="titulo-seccion">Nuestros compromisos</h2>
                    <ul class="compromisos">
                        @foreach($compromisos as $c)
                            <li><strong>{{ $c->titulo }}</strong><span>{{ $c->texto }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        @if(trim(strip_tags($resto)) !== '')
            <section class="seccion">
                <div class="contenedor"><div class="prosa">{!! $resto !!}</div></div>
            </section>
        @endif

        <section class="seccion oscura">
            <div class="contenedor">
                <h2 class="titulo-seccion">¿Necesitás un presupuesto?</h2>
                <p class="bajada">Cargá tu lista de materiales y te respondemos con precio y disponibilidad en el día.</p>
                <div class="botonera">
                    <a class="btn btn-amarillo" href="{{ url('/contacto') }}">Pedir cotización</a>
                    @if(\App\Models\Page::seccionPublicada('productos'))<a class="btn btn-linea" href="{{ url('/productos') }}">Ver productos</a>@endif
                    <a class="btn btn-linea" href="{{ Contacto::whatsappUrl($mensaje) }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
