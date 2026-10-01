@props(['media', 'sizes' => '100vw', 'alt' => null, 'cargaDiferida' => true])
@php
    // Foto de la biblioteca de medios con sus variantes responsivas (srcset real, no un original de 1920 px en el móvil).
    $src = $media->conversionUrl('medium') ?? $media->url();
@endphp
<img {{ $attributes }} src="{{ $src }}" @if($media->srcset()) srcset="{{ $media->srcset() }}" sizes="{{ $sizes }}" @endif
     alt="{{ $alt ?? $media->alt }}" @if($cargaDiferida) loading="lazy" decoding="async" @endif>
