@php
    use App\Support\Contacto;
    $email = Contacto::email();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sitio en mantenimiento · Hierro Metal S.R.L.</title>
    @fonts
    @vite(['resources/css/app.css'])
</head>
<body>
    <main id="contenido" class="oscura" style="min-height:100dvh;display:grid;place-items:center;text-align:left">
        <div class="contenedor" style="max-width:640px">
            <img src="{{ asset('images/logo-hierro-metal.svg') }}" alt="Hierro Metal S.R.L." width="211" height="90" style="background:var(--color-blanco);padding:8px;margin-bottom:30px">
            <p class="rotulo">Mantenimiento</p>
            <h1 class="titulo-seccion">El sitio está en mantenimiento</h1>
            <p class="bajada">Estamos actualizando el contenido. Volvé a intentarlo en unos minutos. Si necesitás algo ahora, escribinos o llamanos.</p>
            <div class="botonera">
                <a class="btn btn-amarillo" href="{{ Contacto::telefonoHref() }}">{{ Contacto::telefono() }}</a>
                <a class="btn btn-linea" href="{{ Contacto::whatsappUrl() }}">WhatsApp</a>
                @if($email)<a class="btn btn-linea" href="mailto:{{ $email }}">{{ $email }}</a>@endif
            </div>
        </div>
    </main>
</body>
</html>
