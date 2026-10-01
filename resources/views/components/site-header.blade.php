@php
    $primaryNav = \App\Models\Menu::renderTree('primary');
@endphp
{{-- Marcador de posición: la cabecera real (barra de datos, catálogo, WhatsApp) se maqueta en la Fase 4 con diseno/componentes.css. --}}
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ url('/') }}" aria-label="Hierro Metal S.R.L. — inicio">
            <img src="{{ asset('images/logo-hierro-metal.svg') }}" alt="" width="96" height="64">
        </a>
        <nav aria-label="Navegación principal">
            <ul>
                @foreach($primaryNav as $item)
                    <li><a href="{{ url($item['url']) }}">{{ $item['label'] }}</a></li>
                @endforeach
            </ul>
        </nav>
        <a class="btn btn-primary" href="{{ url('/contacto') }}">Pedir cotización</a>
    </div>
</header>
