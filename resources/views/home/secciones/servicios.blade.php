@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $catalogo = Catalogo::url();
    $email = Contacto::email();
@endphp
<section class="seccion oscura">
    <div class="contenedor">
        <p class="rotulo">{{ sprintf('%02d', $numero) }} — Servicios industriales</p>
        <h2 class="titulo-seccion">Trabajamos el material por vos</h2>
        <p class="bajada">Cortes sobre medida, plegados, perforaciones, fabricación especial de perfiles C y U, galvanización y entrega en obra con flota propia.</p>
        @if($servicios->isNotEmpty())
            <ul class="rejilla-oscura">
                @foreach($servicios as $s)
                    <li><strong>{{ $s->titulo_home ?: $s->nombre }}</strong><span>{{ $s->resumen_home }}</span></li>
                @endforeach
            </ul>
        @endif
        <div class="botonera"><a class="btn btn-amarillo" href="{{ url('/servicios') }}">Ver los servicios</a></div>
    </div>
</section>
