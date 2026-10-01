@props(['actual' => null])
@php
    use App\Models\Menu;
    use App\Support\Catalogo;
    $items = Menu::renderTree('primary');
    $catalogo = Catalogo::url();
    $esActual = fn (string $url): bool => $actual !== null && trim($url, '/') === $actual;
@endphp
<header class="cabecera" x-data="{ abierto: false }" @keydown.escape.window="abierto = false">
    <div class="cabecera-fila">
        <a class="logo" href="{{ url('/') }}" aria-label="Hierro Metal S.R.L. — inicio">
            <img src="{{ asset('images/logo-hierro-metal.svg') }}" alt="" width="96" height="64">
        </a>
        <nav class="nav-escritorio" aria-label="Navegación principal">
            @foreach($items as $item)
                <a href="{{ url($item['url']) }}" @if($esActual($item['url'])) aria-current="page" @endif>{{ $item['label'] }}</a>
            @endforeach
        </nav>
        <div class="acciones-cabecera">
            @if($catalogo)
                <a class="cabecera-catalogo" href="{{ $catalogo }}">↓ Catálogo</a>
            @endif
            <a class="hm-cta" href="{{ url('/contacto') }}">Pedir cotización</a>
            {{-- Sin JavaScript el botón enlaza al menú (:target); con Alpine lo abre y lo cierra. --}}
            <a class="hm-burger" href="#menu-movil" role="button" aria-label="Abrir el menú" aria-controls="menu-movil"
               :aria-expanded="abierto.toString()" @click.prevent="abierto = ! abierto"><i aria-hidden="true"></i></a>
        </div>
    </div>
    <nav class="menu-movil" id="menu-movil" aria-label="Navegación móvil" :class="{ abierto: abierto }">
        @foreach($items as $item)
            <a href="{{ url($item['url']) }}" @click="abierto = false" @if($esActual($item['url'])) aria-current="page" @endif>{{ $item['label'] }}</a>
        @endforeach
        @if($catalogo)
            <a href="{{ $catalogo }}">↓ Descargar catálogo</a>
        @endif
    </nav>
</header>
