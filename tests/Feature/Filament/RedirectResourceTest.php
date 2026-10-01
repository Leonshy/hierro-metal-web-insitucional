<?php

use App\Filament\Resources\Redirects\RedirectResource;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');
    $this->actingAs($this->admin);
});

it('Redirecciones no aplica a este sitio: no está en el menú del panel', function () {
    $this->get('/'.trim(config('sitio.admin_path'), '/'))->assertOk()->assertDontSee('Redirecciones');
});

it('Redirecciones no se puede abrir por URL ni siendo administrador', function () {
    expect(RedirectResource::canAccess())->toBeFalse();

    foreach (['/redirects', '/redirects/create', '/redirects/1/edit'] as $ruta) {
        expect($this->get('/'.trim(config('sitio.admin_path'), '/').$ruta)->status())->toBeIn([403, 404]);
    }
});
