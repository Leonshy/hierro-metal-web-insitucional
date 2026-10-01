@props(['familia'])
<a class="ficha" href="{{ route('productos.show', $familia->slug) }}">
    @if($familia->media)
        <x-foto :media="$familia->media" class="ficha-foto" sizes="(min-width: 1100px) 360px, (min-width: 640px) 45vw, 100vw" />
    @elseif($familia->ilustracion && $familia->ilustracion !== 'ninguna')
        <x-ilustracion :nombre="$familia->ilustracion" class="ilustracion ficha-ilustracion" />
    @endif
    <span class="ficha-cuerpo">
        <span class="ficha-indice">{{ $familia->rotulo() }}</span>
        <span class="ficha-titulo">{{ $familia->nombre }}</span>
        <span class="ficha-texto">{{ $familia->resumen_home }}</span>
        <span class="ficha-enlace">Ver medidas →</span>
    </span>
</a>
