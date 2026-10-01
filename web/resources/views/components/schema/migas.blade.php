@props(['migas' => []])
{{-- JSON-LD BreadcrumbList (docs/08-seo.md §3). `migas`: [['Inicio', '/'], ['Servicios', null]] — la última es la página actual. --}}
@if(count($migas) > 0)
    @php
        $elementos = collect($migas)->values()->map(fn ($m, $i) => array_filter([
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $m[0],
            'item' => url($m[1] ?? request()->path()),
        ]))->all();
    @endphp
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $elementos], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endif
