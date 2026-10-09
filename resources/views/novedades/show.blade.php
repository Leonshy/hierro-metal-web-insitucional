@php
    use App\Support\Contacto;
    $foto = $novedad->media;
    $mensaje = 'Hola, vi la novedad «'.$novedad->titulo.'» y quiero hacer una consulta.';
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $novedad->titulo,
        'description' => $novedad->resumen,
        'datePublished' => $novedad->publicada_en?->toIso8601String(),
        'dateModified' => $novedad->updated_at?->toIso8601String(),
        'mainEntityOfPage' => route('novedades.show', $novedad->slug),
        'publisher' => ['@type' => 'Organization', 'name' => 'Hierro Metal S.R.L.'],
    ];
    if ($foto) {
        $schema['image'] = $foto->conversionUrl('large') ?? $foto->url();
    }
@endphp
<x-layouts.app :title="$novedad->seo_titulo ?: $novedad->titulo.' · Hierro Metal S.R.L.'" :description="$novedad->seo_descripcion ?: $novedad->resumen" og-type="article" :whatsapp="$mensaje"
               :og-image="$foto?->conversionUrl('large') ?? $foto?->url()">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$novedad->titulo" :rotulo="$novedad->fecha()" :bajada="$novedad->resumen" :migas="[['Inicio', '/'], ['Novedades', '/novedades'], [$novedad->titulo, null]]" />

        <article class="seccion-chica">
            <div class="contenedor">
                @if($foto)
                    <x-foto :media="$foto" class="ficha-foto novedad-foto" sizes="(min-width: 1100px) 1000px, 100vw" :carga-diferida="false" />
                @endif
                <div class="prosa">{!! $novedad->contenido !!}</div>
            </div>
        </article>

        @if($otras->isNotEmpty())
            <section class="seccion-chica alterna">
                <div class="contenedor">
                    <p class="rotulo">Más novedades</p>
                    <ul class="rejilla rejilla-novedades">
                        @foreach($otras as $otra)
                            <li><x-novedad-tarjeta :novedad="$otra" /></li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        <section class="seccion oscura">
            <div class="contenedor">
                <h2 class="titulo-seccion">¿Necesitás un presupuesto?</h2>
                <p class="bajada">Cargá tu lista de materiales y te respondemos con precio y disponibilidad en el día.</p>
                <div class="botonera">
                    <a class="btn btn-amarillo" href="{{ url('/contacto') }}">Pedir cotización</a>
                    <a class="btn btn-linea" href="{{ Contacto::whatsappUrl($mensaje) }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                    <a class="btn btn-linea" href="{{ route('novedades.index') }}">← Todas las novedades</a>
                </div>
            </div>
        </section>
    </main>
    @push('scripts')
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endpush
</x-layouts.app>
