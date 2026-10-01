<x-layouts.app title="Pedido recibido — Hierro Metal S.R.L." :indexable="false">
    {{-- Marcador de posición: la página de gracias maquetada se construye en la Fase 4. Llegar acá es la conversión que se mide. --}}
    <main id="contenido" tabindex="-1" class="container section">
        <p class="rotulo">Pedido recibido</p>
        <h1>Recibimos tu pedido</h1>
        <p>Te respondemos con precio, disponibilidad y plazo de entrega en el día. Si lo mandaste fuera de horario, te respondemos cuando abramos.</p>
        <p><a class="btn btn-primary" href="{{ url('/productos') }}">Ver más productos</a></p>
    </main>
</x-layouts.app>
