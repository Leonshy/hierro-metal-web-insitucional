@php
    use App\Support\Contacto;
    $titulo = $encabezado->titulo ?: 'Servicios industriales';
    $cotizar = url('/contacto').'?rubro=servicio-corte';
    $mensaje = 'Hola, quiero consultar por un servicio de taller.';
    // «Los seis servicios»: el número sale de la cantidad real, así no queda desactualizado si el cliente agrega uno.
    $numeros = [1 => 'un', 2 => 'dos', 3 => 'tres', 4 => 'cuatro', 5 => 'cinco', 6 => 'seis', 7 => 'siete', 8 => 'ocho', 9 => 'nueve', 10 => 'diez'];
    $cantidad = $servicios->count();
    $tituloBloque = $cantidad > 1 ? 'Los '.($numeros[$cantidad] ?? $cantidad).' servicios' : 'Qué hacemos';
@endphp
<x-layouts.app :title="$encabezado->seoTitulo ?: $titulo" :description="$encabezado->seoDescripcion" :whatsapp="$mensaje">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina :titulo="$titulo" :bajada="$encabezado->bajada" :migas="[['Inicio', '/'], ['Servicios', null]]">
            <a class="btn btn-amarillo" href="{{ url($encabezado->ctaUrl ?: '/contacto') }}">{{ $encabezado->ctaTexto ?: 'Pedir cotización' }}</a>
            <a class="btn btn-linea" href="{{ url('/productos') }}">Ver productos</a>
        </x-encabezado-pagina>

        <section class="seccion">
            <div class="contenedor">
                <p class="rotulo">Qué hacemos con el material</p>
                <h2 class="titulo-seccion">{{ $tituloBloque }}</h2>
                <ul class="lineas lineas-servicios">
                    @foreach($servicios as $servicio)
                        <li>
                            <strong>{{ $servicio->nombre }}</strong>
                            <span>{{ $servicio->descripcion }}</span>
                            @if(! empty($servicio->usos))<span class="uso">{{ implode(' · ', $servicio->usos) }}</span>@endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        @if($pasos->isNotEmpty())
            <section class="seccion oscura">
                <div class="contenedor">
                    <p class="rotulo">Cómo trabajamos</p>
                    <h2 class="titulo-seccion">De tu plano a la obra</h2>
                    <p class="bajada">Un pedido con servicio de taller sigue siempre los mismos {{ $numeros[$pasos->count()] ?? $pasos->count() }} pasos.</p>
                    <ol class="pasos">
                        @foreach($pasos as $paso)
                            <li><strong>{{ $paso->titulo }}</strong><span>{{ $paso->texto }}</span></li>
                        @endforeach
                    </ol>
                    <div class="botonera">
                        <a class="btn btn-amarillo" href="{{ $cotizar }}">Empezar un pedido</a>
                        <a class="btn btn-linea" href="{{ Contacto::whatsappUrl($mensaje) }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                    </div>
                </div>
            </section>
        @endif

        <section class="seccion-chica amarilla">
            <div class="contenedor">
                <p class="rotulo">Calidad</p>
                <h2 class="titulo-seccion">El trabajo de taller se controla igual que el material</h2>
                <p class="bajada">Materia prima certificada bajo Normas Internacionales del Acero, control de medidas antes de despachar e infraestructura mantenida.</p>
                <div class="botonera"><a class="btn btn-negro" href="{{ url('/calidad') }}">Leer la política de calidad</a></div>
            </div>
        </section>
    </main>
</x-layouts.app>
