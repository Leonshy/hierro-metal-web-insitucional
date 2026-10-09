<?php

use App\Models\Page;
use App\Models\Popup;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

/** Pop-up de imagen con enlace: aparece en el HTML sólo cuando corresponde. */
beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

it('sin pop-ups no hay ventana en la página', function () {
    $this->get('/')->assertOk()->assertDontSee('class="popup"', false);
});

it('un pop-up activo se dibuja con su imagen, su texto alternativo y su enlace', function () {
    Popup::factory()->create(['nombre' => 'Promo', 'texto_alternativo' => '20% de descuento en chapas', 'enlace' => '/productos/chapas', 'donde' => 'inicio']);

    $this->get('/')->assertOk()
        ->assertSee('class="popup"', false)
        ->assertSee('alt="20% de descuento en chapas"', false)
        ->assertSee('href="'.url('/productos/chapas').'"', false);
});

it('un enlace externo se abre en pestaña nueva con rel noopener si así se pidió', function () {
    Popup::factory()->create(['enlace' => 'https://example.com/promo', 'nueva_pestana' => true]);

    $this->get('/')->assertSee('href="https://example.com/promo" target="_blank" rel="noopener"', false);
});

it('sin enlace la imagen no es clickeable', function () {
    Popup::factory()->create(['enlace' => null]);

    $this->get('/')->assertSee('class="popup"', false)->assertDontSee('popup-enlace', false);
});

it('«sólo en el inicio» no aparece en las demás páginas; «en todo el sitio» sí', function () {
    $popup = Popup::factory()->create(['donde' => 'inicio']);

    $this->get('/servicios')->assertDontSee('class="popup"', false);

    $popup->update(['donde' => 'todas']);

    $this->get('/servicios')->assertSee('class="popup"', false);
});

it('nunca aparece en el formulario de cotización', function () {
    Popup::factory()->create(['donde' => 'todas']);

    $this->get('/contacto')->assertOk()->assertDontSee('class="popup"', false);
});

it('respeta que esté activo y su período de vigencia', function () {
    $popup = Popup::factory()->create();

    $popup->update(['activo' => false]);
    $this->get('/')->assertDontSee('class="popup"', false);

    $popup->update(['activo' => true, 'desde' => now()->addDay()]);
    $this->get('/')->assertDontSee('class="popup"', false);

    $popup->update(['desde' => now()->subDay(), 'hasta' => now()->subHour()]);
    $this->get('/')->assertDontSee('class="popup"', false);

    $popup->update(['desde' => now()->subDay(), 'hasta' => now()->addDay()]);
    $this->get('/')->assertSee('class="popup"', false);
});

it('con varios vigentes muestra uno solo: el primero del orden', function () {
    Popup::factory()->create(['nombre' => 'Segundo', 'orden' => 2]);
    Popup::factory()->create(['nombre' => 'Primero', 'orden' => 1]);

    $html = $this->get('/')->getContent();

    expect(substr_count($html, 'class="popup"'))->toBe(1)->and($html)->toContain('aria-label="Primero"');
});

it('un pop-up sin imagen no se dibuja', function () {
    Popup::factory()->create(['media_id' => null]);

    $this->get('/')->assertDontSee('class="popup"', false);
});

it('no aparece en la vista previa de un borrador', function () {
    Popup::factory()->create(['donde' => 'todas']);
    Page::query()->where('slug', 'servicios')->update(['status' => 'draft']);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');

    $this->actingAs($admin)->get('/servicios')->assertOk()->assertDontSee('class="popup"', false);
});
