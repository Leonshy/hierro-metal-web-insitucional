<div style="font-family:Arial,Helvetica,sans-serif;color:#231f20;max-width:560px">
    <h2 style="margin:0 0 12px">Recibimos tu pedido</h2>
    <p style="margin:0 0 12px">Hola{{ $primerNombre !== '' ? ' '.$primerNombre : '' }}, recibimos tu pedido de cotización. Nuestro equipo lo revisa y se pone en contacto con vos.</p>
    <p style="margin:0 0 4px">Si necesitás algo más mientras tanto:</p>
    <ul style="margin:0 0 12px;padding-left:18px">
        <li>Teléfono: {{ $telefono }}</li>
        <li><a href="{{ $whatsappUrl }}">Escribinos por WhatsApp</a></li>
    </ul>
    <p style="margin:16px 0 0;color:#706c64">Hierro Metal S.R.L.<br>Este correo es una confirmación automática: no hace falta que lo respondas.</p>
</div>
