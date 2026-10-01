<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;

/** Dirección del panel según la configuración (en pruebas no tiene por qué ser /panel). */
function panelUrl(string $ruta = ''): string
{
    return '/'.trim(config('sitio.admin_path'), '/').$ruta;
}

it('la pantalla de acceso muestra el logo de Hierro Metal', function () {
    $this->get(panelUrl('/login'))
        ->assertOk()
        ->assertSee('images/logo-hierro-metal.svg', false)
        ->assertSee('Panel Hierro Metal'); // nombre del panel, como texto alternativo del logo
});

it('el logo del panel existe como archivo y es un SVG', function () {
    expect(file_get_contents(public_path('images/logo-hierro-metal.svg')))->toContain('<svg');
});

it('con la sesión iniciada, la barra del panel también lleva el logo', function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');

    $this->actingAs($admin)->get(panelUrl())->assertOk()->assertSee('images/logo-hierro-metal.svg', false);
});
