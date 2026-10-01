@props(['blocks']) {{-- resultado de Page::blocksForLocale(): array de ['type' => string, 'data' => array] --}}
@foreach($blocks as $block)
    @switch($block['type'])
        @case('hero')
            <x-blocks.hero :data="$block['data']" />
            @break
        @case('texto')
            <x-blocks.texto :data="$block['data']" />
            @break
        @case('imagen_texto')
            <x-blocks.imagen-texto :data="$block['data']" />
            @break
        @case('tarjetas')
            <x-blocks.tarjetas :data="$block['data']" />
            @break
        @case('cta')
            <x-blocks.cta :data="$block['data']" />
            @break
        @case('cifras')
            <x-blocks.cifras :data="$block['data']" />
            @break
        @case('faq')
            <x-blocks.faq :data="$block['data']" />
            @break
        @case('video')
            <x-blocks.video :data="$block['data']" />
            @break
        @case('mapa')
            <x-blocks.mapa :data="$block['data']" />
            @break
    @endswitch
@endforeach
