<?php

use App\Filament\Resources\Vendedores\VendedorResource;
use App\Models\User;
use App\Models\Vendedor;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');
    $this->actingAs($this->admin);
});

function rutaPanel(string $ruta): string
{
    return '/'.trim(config('sitio.admin_path'), '/').$ruta;
}

it('Vendedores no aplica a este sitio: no está en el menú del panel', function () {
    $this->get(rutaPanel(''))->assertOk()->assertDontSee('Vendedores');
});

it('Vendedores no se puede abrir por URL ni siendo administrador', function () {
    expect(VendedorResource::canAccess())->toBeFalse();

    foreach (['/vendedores', '/vendedores/create', '/vendedores/1/edit'] as $ruta) {
        expect($this->get(rutaPanel($ruta))->status())->toBeIn([403, 404]);
    }
});

it('el módulo conserva su modelo y su auditoría por si se reactiva', function () {
    $registro = Vendedor::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Vendedor::class)->exists())->toBeTrue();
});
