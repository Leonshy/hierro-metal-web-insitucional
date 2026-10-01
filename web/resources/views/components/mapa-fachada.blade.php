@props(['titulo' => 'Mapa de Hierro Metal S.R.L.'])
@php
    use App\Support\Contacto;
    $embed = Contacto::mapsEmbedUrl();
    $abrir = Contacto::mapsUrl() ?: 'https://www.google.com/maps/search/?api=1&query='.rawurlencode(Contacto::direccionLarga());
@endphp
{{-- ADR 0003: el iframe de Google NO se carga hasta que la persona lo pide. Sin JavaScript queda el enlace «Abrir en Google Maps». --}}
<div x-data="{ cargado: false, ok() { try { return sessionStorage.getItem('hm-mapa') === '1' } catch (e) { return false } }, cargar() { this.cargado = true; try { sessionStorage.setItem('hm-mapa', '1') } catch (e) {} } }" x-init="cargado = ok()">
    <div class="mapa-consentido" x-show="!cargado">
        <p><strong>{{ Contacto::direccionCorta() }}</strong><br>{{ Contacto::ciudad() }}, Central</p>
        <div class="botonera" style="margin-top:0">
            <button type="button" class="btn btn-amarillo" x-cloak x-on:click="cargar()">Cargar mapa</button>
            <a class="btn btn-linea" href="{{ $abrir }}" target="_blank" rel="noopener">Abrir en Google Maps</a>
        </div>
        <p class="nota">El mapa lo provee Google Maps. Al cargarlo, Google puede registrar datos de tu navegación; podés ver el detalle en nuestra <a href="{{ url('/privacidad') }}">política de privacidad</a>.</p>
    </div>
    <template x-if="cargado">
        <iframe src="{{ $embed }}" title="{{ $titulo }}" width="100%" height="460" style="border:0;display:block" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </template>
</div>
