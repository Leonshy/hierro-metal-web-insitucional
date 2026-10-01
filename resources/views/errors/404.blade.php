<x-layouts.app title="Página no encontrada — Hierro Metal S.R.L." :indexable="false">
    <main id="contenido" tabindex="-1">
        <div class="not-found">
            <p class="code" aria-hidden="true">404</p>
            <h1>No encontramos esa página</h1>
            <p class="body-lg">Puede que el enlace haya cambiado. Mirá los productos o escribinos y te ayudamos.</p>
            <div class="cta-row">
                <a class="btn btn-primary" href="{{ url('/productos') }}">Ver productos</a>
                <a class="btn btn-secondary" href="{{ url('/contacto') }}">Pedir cotización</a>
            </div>
        </div>
    </main>
</x-layouts.app>
