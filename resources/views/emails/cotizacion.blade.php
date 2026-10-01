<div style="font-family:Arial,Helvetica,sans-serif;color:#231f20;max-width:560px">
    <h2 style="margin:0 0 12px">Nueva cotización</h2>
    <p style="margin:0 0 4px"><strong>{{ $cotizacion->nombre }}</strong>@if($cotizacion->empresa) · {{ $cotizacion->empresa }}@endif</p>
    <p style="margin:0 0 4px">Teléfono: <a href="{{ $cotizacion->whatsappUrl() }}">{{ $cotizacion->telefono }}</a> (WhatsApp)</p>
    @if($cotizacion->email)<p style="margin:0 0 4px">Correo: {{ $cotizacion->email }}</p>@endif
    @if($cotizacion->rubro)<p style="margin:0 0 4px">Rubro: {{ $cotizacion->rubro }}</p>@endif
    @if($cotizacion->origen)<p style="margin:0 0 12px;color:#706c64">Desde: {{ $cotizacion->origen }}</p>@endif
    <p style="margin:12px 0 4px"><strong>Pedido</strong></p>
    <div style="border:1px solid #a3a5a8;padding:12px;white-space:pre-wrap">{{ $cotizacion->mensaje }}</div>
    @if($cotizacion->adjuntos->isNotEmpty())
        <p style="margin:12px 0 4px"><strong>Archivos adjuntos ({{ $cotizacion->adjuntos->count() }})</strong>: se descargan desde el panel.</p>
        <ul style="margin:0;padding-left:18px">
            @foreach($cotizacion->adjuntos as $adjunto)
                <li>{{ $adjunto->nombre_original }} ({{ $adjunto->tamanoLegible() }})</li>
            @endforeach
        </ul>
    @endif
    <p style="margin:16px 0 0"><a href="{{ $panelUrl }}" style="background:#f9ed31;color:#231f20;padding:10px 16px;text-decoration:none;font-weight:bold">Abrir en el panel</a></p>
</div>
