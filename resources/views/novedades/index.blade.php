@php
    $titulo = $encabezado->titulo ?: 'Novedades';
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo.' · Hierro Metal S.R.L.'" :description="$encabezado->seoDescripcion">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$titulo" :bajada="$encabezado->bajada" :migas="[['Inicio', '/'], ['Novedades', null]]" />

        <section class="seccion-chica">
            <div class="contenedor">
                @if($novedades->isEmpty())
                    <p class="bajada">Todavía no hay novedades para mostrar.</p>
                @else
                    <ul class="rejilla rejilla-novedades">
                        @foreach($novedades as $novedad)
                            <li><x-novedad-tarjeta :novedad="$novedad" /></li>
                        @endforeach
                    </ul>
                    @if($novedades->hasPages())
                        <nav class="paginacion" aria-label="Páginas de novedades">
                            @if($novedades->onFirstPage())<span aria-disabled="true">← Más nuevas</span>@else<a href="{{ $novedades->previousPageUrl() }}" rel="prev">← Más nuevas</a>@endif
                            <span>Página {{ $novedades->currentPage() }} de {{ $novedades->lastPage() }}</span>
                            @if($novedades->hasMorePages())<a href="{{ $novedades->nextPageUrl() }}" rel="next">Más antiguas →</a>@else<span aria-disabled="true">Más antiguas →</span>@endif
                        </nav>
                    @endif
                @endif
            </div>
        </section>
    </main>
</x-layouts.app>
