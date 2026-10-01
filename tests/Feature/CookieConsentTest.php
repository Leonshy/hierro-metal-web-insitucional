<?php

use App\Models\IntegrationSetting;

it('no carga GTM ni Meta Pixel de forma incondicional en el HTML inicial', function () {
    IntegrationSetting::current()->update([
        'ga_enabled' => true, 'google_tag_manager_id' => 'GTM-XXXXXXX',
        'meta_enabled' => true, 'meta_pixel_id' => '123456789',
    ]);

    $response = $this->get('/');

    $response->assertOk();
    // Ningún <script src="...googletagmanager.com/gtm.js..."> ni
    // "connect.facebook.net/.../fbevents.js" incondicional en el HTML — esos
    // scripts los inyecta resources/js/consent.js por código, solo tras
    // consentimiento (docs/08-seo.md §6).
    $response->assertDontSee('googletagmanager.com/gtm.js', false);
    $response->assertDontSee('connect.facebook.net', false);
});

it('muestra el banner de consentimiento con las tres opciones', function () {
    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('Aceptar todo')
        ->assertSee('Rechazar todo')
        ->assertSee('Configurar');
});

it('expone el ID de GTM/Pixel como datos para que el JS de consentimiento los use tras aceptar', function () {
    IntegrationSetting::current()->update([
        'ga_enabled' => true, 'google_tag_manager_id' => 'GTM-XXXXXXX',
        'meta_enabled' => true, 'meta_pixel_id' => '123456789',
    ]);

    $this->get('/')
        ->assertSee('data-gtm-id="GTM-XXXXXXX"', false)
        ->assertSee('data-meta-pixel-id="123456789"', false);
});

it('con las integraciones apagadas no expone ningún ID, aunque esté guardado', function () {
    IntegrationSetting::current()->update([
        'ga_enabled' => false, 'google_tag_manager_id' => 'GTM-XXXXXXX',
        'meta_enabled' => false, 'meta_pixel_id' => '123456789',
    ]);

    $this->get('/')
        ->assertSee('data-gtm-id=""', false)
        ->assertSee('data-meta-pixel-id=""', false);
});

it('el panel de preferencias tiene botón Cerrar, pero sólo visible al reabrir preferencias ya elegidas', function () {
    $html = $this->get('/')->assertOk()->getContent();

    // El botón existe en el panel de preferencias y se muestra únicamente cuando `reopened` es verdadero:
    // la primera vez la persona tiene que decidir, así que no hay forma de cerrar sin elegir.
    expect($html)->toContain('x-show="reopened"')
        ->and($html)->toContain('@click="close()"')
        ->and($html)->toContain('@keydown.escape.window="if (reopened) close()"');
});

it('las casillas de preferencias usan los textos aprobados', function () {
    $this->get('/')
        ->assertSee('Necesarias <small>(siempre activas)</small>', false)
        ->assertSee('Medición de visitas <small>(Google Analytics)</small>', false)
        ->assertSee('Publicidad <small>(Meta: Facebook e Instagram)</small>', false);
});
