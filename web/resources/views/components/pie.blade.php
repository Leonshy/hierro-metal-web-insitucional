@php
    use App\Models\Menu;
    use App\Models\Page;
    use App\Support\Catalogo;
    use App\Support\Contacto;
    $enlaces = collect(Menu::renderTree('footer_secondary'));
    $legales = Page::enlacesLegales();
    $principales = $enlaces->reject(fn ($e) => $e['url'] === '/privacidad')->values();
    $catalogo = Catalogo::url();
    $email = Contacto::email();
@endphp
<footer class="pie">
    <div class="pie-rejilla">
        <div>
            <span class="pie-marca">Hierro <span>Metal</span> S.R.L.</span>
            <p>{{ Contacto::descripcionCorta() }}</p>
        </div>
        <nav aria-label="Pie: secciones">
            @foreach($principales->take(4) as $e)<a href="{{ url($e['url']) }}">{{ $e['label'] }}</a>@endforeach
        </nav>
        <nav aria-label="Pie: más información">
            @foreach($principales->slice(4) as $e)<a href="{{ url($e['url']) }}">{{ $e['label'] }}</a>@endforeach
            @if($catalogo)<a href="{{ $catalogo }}">{{ Catalogo::textoBoton() }}</a>@endif
        </nav>
        <div class="columna">
            <a href="{{ Contacto::telefonoHref() }}">{{ Contacto::telefono() }}</a>
            @if($email)<a href="mailto:{{ $email }}">{{ $email }}</a>@endif
            @if(Contacto::mapsUrl())
                <a href="{{ Contacto::mapsUrl() }}" target="_blank" rel="noopener">{{ Contacto::direccionCorta() }}, {{ Contacto::ciudad() }}, Central →</a>
            @else
                <p>{{ Contacto::direccionCorta() }}, {{ Contacto::ciudad() }}, Central</p>
            @endif
        </div>
    </div>
    <div class="pie-legal">
        <div class="contenedor">
            <p>© {{ now()->year }} Hierro Metal S.R.L. · Todos los derechos reservados</p>
            @if($legales !== [])
                <nav class="pie-legales" aria-label="Páginas legales">
                    @foreach($legales as $legal)<a href="{{ url($legal['url']) }}">{{ $legal['label'] }}</a>@endforeach
                </nav>
            @endif
        </div>
    </div>
</footer>
