@props(['nombre'])
@php
    // Ilustraciones de acero del cliente, en línea y con tokens (resources/svg). Son livianas y no bloquean la carga.
    $archivo = resource_path("svg/ilustracion-{$nombre}.svg");
    $svg = is_file($archivo) ? file_get_contents($archivo) : '';
@endphp
<div {{ $attributes->class(['ilustracion-caja']) }}>{!! $svg !!}</div>
