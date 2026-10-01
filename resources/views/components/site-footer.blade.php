@php
    $secondary = \App\Models\Menu::renderTree('footer_secondary');
@endphp
{{-- Marcador de posición: el pie real se maqueta en la Fase 4. --}}
<footer class="site-footer">
    <div class="container">
        <p>Hierro Metal S.R.L. — importación y venta de materiales de construcción metálicos y metalúrgicos.</p>
        <ul>
            @foreach($secondary as $link)
                <li><a href="{{ url($link['url']) }}">{{ $link['label'] }}</a></li>
            @endforeach
        </ul>
        <p>&copy; {{ now()->year }} Hierro Metal S.R.L. · Todos los derechos reservados</p>
    </div>
</footer>
