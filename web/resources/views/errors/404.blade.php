<x-layouts.app title="Página no encontrada · Hierro Metal S.R.L." :indexable="false">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina rotulo="Error 404" titulo="No encontramos esa página" bajada="Puede que el enlace haya cambiado. Mirá los productos o escribinos y te ayudamos." clima="amarilla">
            @if(\App\Models\Page::seccionPublicada('productos'))<a class="btn btn-negro" href="{{ url('/productos') }}">Ver productos</a>@endif
            <a class="btn btn-linea" href="{{ \App\Support\Contacto::whatsappUrl('Hola, no encontré una página del sitio.') }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
        </x-encabezado-pagina>
    </main>
</x-layouts.app>
