@php
    use App\Support\Contacto;
    $instagram = Contacto::instagram();
    $facebook = Contacto::facebook();
@endphp
<div class="barra-datos">
    <div class="contenedor">
        <div class="barra-direccion"><i aria-hidden="true"></i>{{ Contacto::direccionCorta() }} · {{ Contacto::ciudad() }}, Central</div>
        <div class="barra-enlaces">
            <a href="{{ Contacto::telefonoHref() }}">{{ Contacto::telefono() }}</a>
            @if($instagram)<a href="{{ $instagram }}" target="_blank" rel="noopener">Instagram</a>@endif
            @if($facebook)<a href="{{ $facebook }}" target="_blank" rel="noopener">Facebook</a>@endif
        </div>
    </div>
</div>
