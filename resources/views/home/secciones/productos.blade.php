@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $catalogo = Catalogo::url();
    $email = Contacto::email();
@endphp
<section class="seccion">
    <div class="contenedor">
        <p class="rotulo">{{ sprintf('%02d', $numero) }} — Productos</p>
        <h2 class="titulo-seccion">Qué comercializamos</h2>
        <p class="bajada">Cinco familias de materiales metálicos y metalúrgicos con stock permanente. Entrá al catálogo para ver espesores, medidas y normas de cada línea.</p>
        <ul class="rejilla">
            @foreach($familias as $familia)
                <li>
                    <x-familia-tarjeta :familia="$familia" />
                </li>
            @endforeach
            @if($catalogo)
                <li class="ficha-destacada">
                    <div class="caja">
                        <span class="ficha-indice">Catálogo</span>
                        <span class="ficha-titulo">Catálogo de materiales</span>
                        <span class="ficha-texto">Nuestro catálogo general en PDF. Confirmá medidas, espesores y stock actual por WhatsApp antes de comprar.</span>
                        <a class="btn btn-amarillo" href="{{ $catalogo }}">↓ Descargar PDF</a>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</section>
