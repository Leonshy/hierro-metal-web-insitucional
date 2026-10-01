@php
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $catalogo = Catalogo::url();
@endphp
<x-layouts.app title="Pedido recibido · Hierro Metal S.R.L." :indexable="false">
    {{-- Llegar acá es la conversión que se mide (Meta Pixel / Conversions API, docs/08 §6). --}}
    <main id="contenido" tabindex="-1">
        <section class="encabezado-pagina amarilla">
            <div class="contenedor">
                <p class="rotulo">Pedido recibido</p>
                <h1>Recibimos tu pedido</h1>
                <p class="bajada">Te respondemos con precio, disponibilidad y plazo de entrega en el día. Si lo mandaste fuera de horario, te respondemos cuando abramos.</p>
            </div>
        </section>

        <section class="seccion">
            <div class="contenedor">
                @if($pasos->isNotEmpty())
                    <p class="rotulo">Qué pasa ahora</p>
                    <ol class="pasos">
                        @foreach($pasos as $paso)
                            <li><strong>{{ $paso->titulo }}</strong><span>{{ $paso->texto }}</span></li>
                        @endforeach
                    </ol>
                @endif
                <p class="bajada">¿Con apuro? Escribinos por WhatsApp al {{ Contacto::telefono() }}.</p>
                <div class="botonera">
                    <a class="btn btn-amarillo" href="{{ Contacto::whatsappUrl('Hola, acabo de mandar un pedido de cotización desde el sitio.') }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                    <a class="btn btn-linea" href="{{ url('/productos') }}">Ver más productos</a>
                    @if($catalogo)<a class="btn btn-linea" href="{{ $catalogo }}">↓ {{ Catalogo::textoBoton() }}</a>@endif
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
