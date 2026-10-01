@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $catalogo = Catalogo::url();
    $email = Contacto::email();
@endphp
<section class="seccion-chica amarilla">
    <div class="contenedor">
        <p class="rotulo">{{ sprintf('%02d', $numero) }} — Política de calidad</p>
        <h2 class="titulo-seccion">Materia prima certificada bajo Normas Internacionales del Acero</h2>
        <p class="bajada">Control de calidad estricto, infraestructura mantenida y mejora continua de productos y procesos.</p>
        <div class="botonera"><a class="btn btn-negro" href="{{ url('/calidad') }}">Leer la política completa</a></div>
    </div>
</section>
