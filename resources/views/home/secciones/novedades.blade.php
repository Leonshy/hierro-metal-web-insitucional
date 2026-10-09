{{-- Adelanto de las últimas novedades. La portada sólo incluye esta sección si hay al menos una visible. --}}
@if($novedades->isNotEmpty())
<section class="seccion alterna">
    <div class="contenedor">
        <p class="rotulo">{{ sprintf('%02d', $numero) }} — Novedades</p>
        <h2 class="titulo-seccion">Lo último en Hierro Metal</h2>
        <ul class="rejilla rejilla-novedades">
            @foreach($novedades as $novedad)
                <li><x-novedad-tarjeta :novedad="$novedad" /></li>
            @endforeach
        </ul>
        <div class="botonera"><a class="btn btn-amarillo" href="{{ route('novedades.index') }}">Ver todas las novedades</a></div>
    </div>
</section>
@endif
