@props(['titulo', 'rotulo' => null, 'bajada' => null, 'migas' => [], 'clima' => ''])
{{-- Encabezado de las páginas internas. `migas`: [['Inicio', '/'], ['Servicios', null]] — la última es la página actual. --}}
<section class="encabezado-pagina {{ $clima }}">
    <div class="contenedor">
        @if(count($migas))
            <x-schema.migas :migas="$migas" />
            <nav class="migas" aria-label="Migas de pan">
                @foreach($migas as [$etiqueta, $enlace])
                    @if($enlace)<a href="{{ url($enlace) }}">{{ $etiqueta }}</a> / @else<span aria-current="page">{{ $etiqueta }}</span>@endif
                @endforeach
            </nav>
        @endif
        @if($rotulo)<p class="rotulo">{{ $rotulo }}</p>@endif
        <h1>{{ $titulo }}</h1>
        @if($bajada)<p class="bajada">{{ $bajada }}</p>@endif
        @if(! $slot->isEmpty())<div class="botonera">{{ $slot }}</div>@endif
    </div>
</section>
