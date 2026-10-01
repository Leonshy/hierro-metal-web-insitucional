<?php

use App\Models\IntegrationSetting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/** Pedido válido: la marca de tiempo tiene 10 segundos de antigüedad. */
function pedidoParaMeta(): array
{
    return [
        'nombre' => 'Persona de Prueba',
        'telefono' => '0981 000 000',
        'email' => 'prueba@ejemplo.test',
        'mensaje' => 'Necesito chapas.',
        'acepto' => '1',
        'sitio_web' => '',
        '_t' => Crypt::encryptString((string) (now()->timestamp - 10)),
    ];
}

function metaActivo(?string $token = 'test-token', bool $activo = true): void
{
    IntegrationSetting::current()->update(['meta_enabled' => $activo, 'meta_pixel_id' => '123456789', 'meta_capi_access_token' => $token]);
}

beforeEach(function () {
    Mail::fake();
    SiteSetting::set('email_notificacion_cotizaciones', 'ventas@ejemplo.test');
});

it('no manda el evento Lead a Meta si el visitante no aceptó cookies de publicidad', function () {
    metaActivo();
    Http::fake();

    $this->post('/contacto', pedidoParaMeta());

    Http::assertNothingSent();
});

it('el consentimiento que escribe el banner (cookie sin cifrar) llega al servidor', function () {
    metaActivo();
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1], 200)]);

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')->post('/contacto', pedidoParaMeta());

    Http::assertSent(fn ($request) => str_contains($request->url(), 'graph.facebook.com')
        && $request['data'][0]['event_name'] === 'Lead'
        && ! empty($request['data'][0]['event_id']));
});

it('por defecto el evento a Meta no lleva teléfono ni correo, ni siquiera con hash', function () {
    metaActivo();
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1], 200)]);

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')->post('/contacto', pedidoParaMeta());

    Http::assertSent(function ($request) {
        $usuario = $request['data'][0]['user_data'];

        return ! array_key_exists('em', $usuario) && ! array_key_exists('ph', $usuario);
    });
});

it('si se activa expresamente, el evento lleva el correo y el teléfono con hash', function () {
    metaActivo();
    config(['sitio.cotizaciones.meta_enviar_datos_personales' => true]);
    Http::fake(['graph.facebook.com/*' => Http::response(['events_received' => 1], 200)]);

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')->post('/contacto', pedidoParaMeta());

    Http::assertSent(function ($request) {
        $usuario = $request['data'][0]['user_data'];

        return $usuario['em'] === [hash('sha256', 'prueba@ejemplo.test')] && isset($usuario['ph']);
    });
});

it('un intento de spam nunca llega a Meta, aunque haya consentimiento', function () {
    metaActivo();
    Http::fake();

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')
        ->post('/contacto', array_merge(pedidoParaMeta(), ['sitio_web' => 'robot']));

    Http::assertNothingSent();
});

it('sin token de acceso configurado no manda ninguna llamada, aunque haya consentimiento', function () {
    metaActivo(token: null);
    Http::fake();

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')->post('/contacto', pedidoParaMeta());

    Http::assertNothingSent();
});

it('con la integración apagada desde el panel no manda ninguna llamada, aunque haya token y consentimiento', function () {
    metaActivo(activo: false);
    Http::fake();

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')->post('/contacto', pedidoParaMeta());

    Http::assertNothingSent();
});
