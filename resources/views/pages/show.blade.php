@php
    use App\Support\Contacto;
    // Página de texto del panel (Privacidad y las que cree el cliente): si el primer bloque es un hero, sus textos
    // arman el encabezado y el resto de los bloques va debajo; si no, el encabezado lleva el título de la página.
    $hero = ($blocks[0]['type'] ?? null) === 'hero' ? ($blocks[0]['data'] ?? []) : null;
    $resto = $hero ? array_slice($blocks, 1) : $blocks;
    $migas = collect($breadcrumbs)->map(fn ($m, $i) => [$m['label'], $i === count($breadcrumbs) - 1 ? null : $m['url']])->prepend(['Inicio', '/'])->all();
    $titulo = (string) ($hero['title'] ?? $page->title);
@endphp
<x-layouts.app
    :title="$page->effectiveSeoTitle().' · Hierro Metal S.R.L.'"
    :description="$page->getTranslation('seo_description', app()->getLocale())"
    :indexable="$page->is_indexable"
    :canonical="$page->canonical_url ?: null"
    :og-image="$page->seoImage?->conversionUrl('w1200') ?? $page->seoImage?->url() ?? $page->coverMedia?->conversionUrl('w1200') ?? $page->coverMedia?->url()"
>
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$titulo" :bajada="$hero['subtitle'] ?? null" :migas="$migas">
            @if(! empty($hero['cta_label']))<a class="btn btn-amarillo" href="{{ url($hero['cta_url'] ?? '/contacto') }}">{{ $hero['cta_label'] }}</a>@endif
        </x-encabezado-pagina>

        <x-page-blocks :blocks="$resto" />

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
