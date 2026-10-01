<x-layouts.app title="Error del servidor · Hierro Metal S.R.L." :indexable="false">
    <main id="contenido" tabindex="-1">
        <x-encabezado-pagina rotulo="Error 500" titulo="Algo salió mal" bajada="Tuvimos un problema técnico. Ya estamos al tanto: probá de nuevo en unos minutos o escribinos por WhatsApp." clima="amarilla">
            <a class="btn btn-negro" href="{{ url('/') }}">Ir al inicio</a>
            <a class="btn btn-linea" href="{{ \App\Support\Contacto::whatsappUrl() }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
        </x-encabezado-pagina>
    </main>
</x-layouts.app>
