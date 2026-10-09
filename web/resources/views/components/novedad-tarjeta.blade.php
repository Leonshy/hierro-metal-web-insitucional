@props(['novedad'])
<a class="ficha" href="{{ route('novedades.show', $novedad->slug) }}">
    @if($novedad->media)
        <x-foto :media="$novedad->media" class="ficha-foto" sizes="(min-width: 1100px) 360px, (min-width: 640px) 45vw, 100vw" />
    @endif
    <span class="ficha-cuerpo">
        <span class="ficha-indice">{{ $novedad->fecha() }}</span>
        <span class="ficha-titulo">{{ $novedad->titulo }}</span>
        <span class="ficha-texto">{{ $novedad->resumen }}</span>
        <span class="ficha-enlace">Leer más →</span>
    </span>
</a>
