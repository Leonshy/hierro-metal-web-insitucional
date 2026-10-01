@php
    use App\Support\Contacto;
    $titulo = $encabezado->titulo ?: 'Preguntas frecuentes';
    $mensaje = 'Hola, tengo una consulta que no encontré en las preguntas frecuentes.';
    // Datos estructurados para buscadores: el mismo texto que se ve, sin etiquetas.
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f->pregunta,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(html_entity_decode(strip_tags((string) $f->respuesta)))],
        ])->all(),
    ];
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo" :description="$encabezado->seoDescripcion" :whatsapp="$mensaje">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$titulo" :bajada="$encabezado->bajada" :migas="[['Inicio', '/'], ['Preguntas frecuentes', null]]" />

        <section class="seccion-chica">
            <div class="contenedor">
                <div class="faq">
                    @foreach($faqs as $faq)
                        <details>
                            <summary>{{ $faq->pregunta }}</summary>
                            <div class="respuesta">{!! $faq->respuesta !!}</div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="seccion oscura">
            <div class="contenedor">
                <h2 class="titulo-seccion">¿Necesitás un presupuesto?</h2>
                <p class="bajada">Cargá tu lista de materiales y te respondemos con precio y disponibilidad en el día.</p>
                <div class="botonera">
                    <a class="btn btn-amarillo" href="{{ url('/contacto') }}">Pedir cotización</a>
                    <a class="btn btn-linea" href="{{ Contacto::whatsappUrl($mensaje) }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                </div>
            </div>
        </section>
    </main>
    @if($faqs->isNotEmpty())
        @push('scripts')
            <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @endpush
    @endif
</x-layouts.app>
