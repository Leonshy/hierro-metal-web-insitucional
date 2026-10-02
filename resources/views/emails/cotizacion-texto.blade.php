Nueva cotización
{{ $cotizacion->nombre }}{{ $cotizacion->empresa ? ' · '.$cotizacion->empresa : '' }}
@if($cotizacion->ci_ruc)CI o RUC: {{ $cotizacion->ci_ruc }}
@endif
Teléfono: {{ $cotizacion->telefono }}
@if($cotizacion->email)Correo: {{ $cotizacion->email }}
@endif
@if($cotizacion->rubro)Rubro: {{ $cotizacion->rubro }}
@endif

Pedido:
{{ $cotizacion->mensaje }}
@if($cotizacion->adjuntos->isNotEmpty())

Archivos adjuntos ({{ $cotizacion->adjuntos->count() }}), para descargar desde el panel:
@foreach($cotizacion->adjuntos as $adjunto)
- {{ $adjunto->nombre_original }} ({{ $adjunto->tamanoLegible() }})
@endforeach
@endif

Abrir en el panel: {{ $panelUrl }}
