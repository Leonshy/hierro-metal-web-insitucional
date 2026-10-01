@php
    use App\Support\Contacto;
    // Página de texto del panel (Privacidad y las que cree el cliente): el encabezado sale del bloque de encabezado
    // y el resto de la página son sus textos, en el orden en que se cargaron.
    $hero = collect($blocks)->firstWhere('type', 'hero')['data'] ?? [];
    $textos = collect($blocks)->where('type', 'texto')->values();
    $migas = collect($breadcrumbs)->map(fn ($m, $i) => [$m['label'], $i === count($breadcrumbs) - 1 ? null : $m['url']])->prepend(['Inicio', '/'])->all();
    $titulo = (string) ($hero['title'] ?? $page->title);
@endphp
<x-layouts.app
    :title="$page->effectiveSeoTitle().' · Hierro Metal S.R.L.'"
    :description="$page->getTranslation('seo_description', app()->getLocale())"
    :indexable="$page->is_indexable"
    :canonical="$page->canonical_url ?: null"
    :og-image="$page->seoImage?->conversionUrl('w1200') ?? $page->seoImage?->url()"
>
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$titulo" :bajada="$hero['subtitle'] ?? null" :migas="$migas" />

        @foreach($textos as $texto)
            <x-blocks.texto :data="$texto['data']" />
        @endforeach

        <section class="seccion-chica alterna">
            <div class="contenedor">
                <div class="botonera" style="margin-top:0">
                    <a class="btn btn-amarillo" href="{{ url('/contacto') }}">Pedir cotización</a>
                    <a class="btn btn-linea" href="{{ Contacto::whatsappUrl() }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
