@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $catalogo = Catalogo::url();
    $email = Contacto::email();
    $titulo = $encabezado->titulo ?: 'Hierro Metal S.R.L.';
    $foto = $encabezado->media();
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo" :description="$encabezado->seoDescripcion" :og-image="$foto?->conversionUrl('large') ?? $foto?->url()">
    <main id="contenido" tabindex="-1">
        <section class="oscura @if($foto) portada-con-foto @endif">
            @if($foto)
                {{-- Foto del catálogo como fondo del hero, editable desde el panel (página «inicio», bloque hero). Decorativa:
                     el texto ya dice qué se vende. La capa oscura va en CSS (.portada-con-foto::after) para que se lea el texto. --}}
                <x-foto :media="$foto" class="foto-portada" sizes="100vw" alt="" :carga-diferida="false" fetchpriority="high" />
            @endif
            <div class="contenedor">
                <div class="portada-texto">
                    <span class="insignia">{{ $encabezado->dato('insignia', 'Importación y venta · Mayorista y minorista') }}</span>
                    <h1>{{ $titulo }}</h1>
                    @if($encabezado->bajada)<p class="bajada">{{ $encabezado->bajada }}</p>@endif
                    <div class="botonera">
                        <a class="btn btn-amarillo" href="{{ url($encabezado->ctaUrl ?: '/contacto') }}">{{ $encabezado->ctaTexto ?: 'Pedir cotización' }}</a>
                        <a class="btn btn-linea" href="{{ url('/productos') }}">Ver productos y medidas</a>
                        @if($catalogo)<a class="btn btn-linea" href="{{ $catalogo }}">↓ {{ Catalogo::textoBoton() }}</a>@endif
                    </div>
                    <p class="nota-portada">{{ $encabezado->dato('nota', 'Todas las chapas con certificado de calidad del fabricante') }}</p>
                </div>
            </div>
            @unless($foto)
                <x-ilustracion nombre="portada" class="ilustracion ilustracion-portada" />
            @endunless
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

        {{-- Secciones de la portada: sólo las activas, en el orden que decidió el cliente desde el panel (Inicio). --}}
        @foreach($secciones as $seccion)
            @includeIf('home.secciones.'.$seccion->clave, ['numero' => $loop->iteration])
        @endforeach
    </main>
</x-layouts.app>
