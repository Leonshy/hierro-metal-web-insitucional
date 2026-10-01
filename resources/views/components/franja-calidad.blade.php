@props(['donde', 'rotulo'])
@php
    // Textos editables desde el panel (sección Calidad); si faltan, los de siempre.
    $franja = \App\Support\FranjaCalidad::de($donde);
@endphp
<section class="seccion-chica amarilla">
    <div class="contenedor">
        <p class="rotulo">{{ $rotulo }}</p>
        <h2 class="titulo-seccion">{{ $franja['titulo'] }}</h2>
        <p class="bajada">{{ $franja['bajada'] }}</p>
        <div class="botonera"><a class="btn btn-negro" href="{{ url('/calidad') }}">{{ $franja['boton'] }}</a></div>
    </div>
</section>
