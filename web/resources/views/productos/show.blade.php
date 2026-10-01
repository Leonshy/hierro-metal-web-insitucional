@php
    use App\Support\Catalogo;
    $catalogo = Catalogo::url();
    $cotizar = url('/contacto').'?rubro='.$familia->slug;
    // «Chapas de acero» → «chapas»: en los botones va el nombre corto de la familia.
    $corto = mb_strtolower(['chapas' => 'chapas', 'perfiles' => 'perfiles', 'tubos' => 'tubos y caños', 'varillas' => 'varillas y barras', 'accesorios' => 'accesorios de cañería'][$familia->slug] ?? $familia->nombre);
    $eleccion = ['chapas' => 'Elegí tu chapa', 'perfiles' => 'Elegí tu perfil', 'tubos' => 'Elegí tu tubo o caño', 'varillas' => 'Elegí tu varilla o barra', 'accesorios' => 'Elegí tu accesorio'][$familia->slug] ?? 'Elegí tu línea';
@endphp
<x-layouts.app :title="$familia->seo_titulo ?: $familia->nombre.' · Hierro Metal S.R.L.'" :description="$familia->seo_descripcion" :whatsapp="$familia->mensajeWhatsapp()" :og-image="$familia->media?->conversionUrl('large') ?? $familia->media?->url()">
    <main id="contenido" tabindex="-1">
        <section class="seccion-chica linea-abajo">
            <div class="contenedor">
                <x-schema.migas :migas="[['Inicio', '/'], ['Productos', '/productos'], [$familia->nombre, null]]" />
                <nav class="migas" aria-label="Migas de pan">
                    <a href="{{ url('/') }}">Inicio</a> / <a href="{{ route('productos.index') }}">Productos</a> / <span aria-current="page">{{ $familia->nombre }}</span>
                </nav>
                <div class="familia-cabecera">
                    <div>
                        <p class="rotulo">{{ $familia->rotulo() }}</p>
                        <h1>{{ $familia->nombre }}</h1>
                        <p class="bajada">{{ $familia->bajada }}</p>
                        <div class="botonera">
                            <a class="btn btn-amarillo" href="{{ $cotizar }}">Pedir cotización de {{ $corto }}</a>
                            @if($catalogo)<a class="btn btn-linea" href="{{ $catalogo }}">↓ {{ Catalogo::textoBoton() }}</a>@endif
                        </div>
                    </div>
                    @if($familia->media)
                        <x-foto :media="$familia->media" class="ficha-foto" sizes="(min-width: 860px) 45vw, 100vw" :carga-diferida="false" />
                    @elseif($familia->ilustracion && $familia->ilustracion !== 'ninguna')
                        <x-ilustracion :nombre="$familia->ilustracion" class="ilustracion ficha-ilustracion" />
                    @endif
                </div>
                <ul class="indice" aria-label="Familias">
                    @foreach($familias as $otra)
                        <li><a href="{{ route('productos.show', $otra->slug) }}" @if($otra->is($familia)) aria-current="true" @endif>{{ sprintf('%02d', $loop->iteration) }} · {{ $otra->nombre }}</a></li>
                    @endforeach
                </ul>
            </div>
        </section>

        <section class="seccion">
            <div class="contenedor">
                <p class="rotulo">Líneas y medidas</p>
                <h2 class="titulo-seccion" style="font-size:var(--text-familia)">{{ $eleccion }}</h2>
                <div style="margin-top:30px">
                    @foreach($lineas as $linea)
                        @php
                            $tablas = $linea->tablas();
                            $cuenta = count($tablas) > 1 ? count($tablas).' tablas' : (count($tablas) === 1 ? 'medidas' : 'consultar');
                        @endphp
                        <details class="linea-medidas" @if($loop->first) open @endif>
                            <summary>
                                <span class="nombre">{{ $linea->nombre }}</span>
                                <span class="cuenta">{{ $cuenta }}<svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span>
                            </summary>
                            <div class="linea-cuerpo">
                                <p>{{ $linea->descripcion }}</p>
                                @if(! empty($linea->usos))<span class="uso">{{ implode(' · ', $linea->usos) }}</span>@endif

                                @foreach($tablas as $tabla)
                                    @if($tabla['titulo'] && count($tablas) > 1)<h3 class="rotulo" style="margin:22px 0 0">{{ $tabla['titulo'] }}</h3>@endif
                                    @if($tabla['modo'] === 'tabla')<p class="pista-scroll">Deslizá para ver todas las columnas →</p>@endif
                                    <div class="tabla-scroll" role="region" aria-label="Medidas de {{ mb_strtolower($tabla['titulo'] ?: $linea->nombre) }}" tabindex="0">
                                        <table class="tabla-medidas @if($tabla['modo'] === 'lista') tabla-lista @endif">
                                            <caption>Medidas de {{ mb_strtolower($tabla['titulo'] ?: $linea->nombre) }}</caption>
                                            <thead><tr>@foreach($tabla['columnas'] as $columna)<th scope="col">{{ $columna }}</th>@endforeach</tr></thead>
                                            <tbody>
                                                @foreach($tabla['filas'] as $fila)
                                                    <tr>@foreach($fila as $i => $celda)@if($i === 0)<th scope="row">{{ $celda }}</th>@else<td>{{ $celda }}</td>@endif @endforeach</tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach

                                @if($linea->nota_medidas)<p class="nota-medida">{{ $linea->nota_medidas }}</p>@endif

                                @if(count($tablas) > 0)
                                    <p class="fuera-lista">¿No ves la medida que necesitás? Consultanos con tu lista y te confirmamos stock y precio en el día.</p>
                                    <div class="botonera"><a class="btn btn-negro" href="{{ $cotizar }}">Cotizar esta línea</a></div>
                                @else
                                    <p class="fuera-lista">Consultanos las medidas disponibles y el precio.</p>
                                    <div class="botonera"><a class="btn btn-negro" href="{{ $cotizar }}">Pedir medidas y precio</a></div>
                                @endif
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="seccion oscura">
            <div class="contenedor">
                <p class="rotulo">Siguiente paso</p>
                <h2 class="titulo-seccion">Pasanos tu lista de materiales</h2>
                <p class="bajada">Mandanos el despiece, el plano o simplemente la lista escrita. Te respondemos con precio, disponibilidad y plazo de entrega en el día.</p>
                <div class="botonera">
                    <a class="btn btn-amarillo" href="{{ $cotizar }}">Pedir cotización</a>
                    <a class="btn btn-linea" href="{{ \App\Support\Contacto::whatsappUrl($familia->mensajeWhatsapp()) }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                </div>
                <ul class="rejilla-oscura" style="margin-top:34px">
                    <li><strong>¿Necesitás corte a medida?</strong><span>Cortamos, plegamos y perforamos el material antes de entregarlo.</span></li>
                    <li><strong>¿Lo llevamos a la obra?</strong><span>Entregamos con flota propia de camiones.</span></li>
                </ul>
            </div>
        </section>
    </main>
</x-layouts.app>
