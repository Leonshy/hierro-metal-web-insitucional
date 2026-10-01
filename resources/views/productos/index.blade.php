@php
    use App\Support\Catalogo;
    $catalogo = Catalogo::url();
    $titulo = $encabezado->titulo ?: 'Productos';
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo" :description="$encabezado->seoDescripcion">
    <main id="contenido" tabindex="-1">
        <section class="seccion-chica linea-abajo encabezado-pagina">
            <div class="contenedor">
                <x-schema.migas :migas="[['Inicio', '/'], ['Productos', null]]" />
                <nav class="migas" aria-label="Migas de pan"><a href="{{ url('/') }}">Inicio</a> / <span aria-current="page">Productos</span></nav>
                <h1>{{ $titulo }}</h1>
                @if($encabezado->bajada)<p class="bajada">{{ $encabezado->bajada }}</p>@endif
                <div class="botonera">
                    <a class="btn btn-amarillo" href="{{ url($encabezado->ctaUrl ?: '/contacto') }}">{{ $encabezado->ctaTexto ?: 'Pedir cotización' }}</a>
                    @if($catalogo)<a class="btn btn-linea" href="{{ $catalogo }}">↓ {{ Catalogo::textoBoton() }}</a>@endif
                </div>
            </div>
        </section>
        <section class="seccion">
            <div class="contenedor">
                <ul class="rejilla">
                    @foreach($familias as $familia)
                        <li><x-familia-tarjeta :familia="$familia" /></li>
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
    </main>
</x-layouts.app>
