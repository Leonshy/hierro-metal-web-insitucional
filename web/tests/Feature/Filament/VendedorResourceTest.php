<?php

use App\Filament\Resources\Vendedores\Pages\CreateVendedor;
use App\Filament\Resources\Vendedores\Pages\EditVendedor;
use App\Filament\Resources\Vendedores\Pages\ListVendedores;
use App\Models\User;
use App\Models\Vendedor;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista vendedores', function () {
    Vendedor::factory()->count(3)->create();

    $this->livewire(ListVendedores::class)->assertSuccessful();
});

it('crea un registro de vendedores y lo deja al final del orden', function () {
    Vendedor::factory()->create(['orden' => 4]);

    $this->livewire(CreateVendedor::class)
        ->fillForm([
            'nombre' => 'Persona de prueba',
            'telefono' => '0981 000 000',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Vendedor::query()->where('nombre', 'Persona de prueba')->firstOrFail();

    expect($nuevo->orden)->toBe(5);
});

it('edita un registro de vendedores', function () {
    $registro = Vendedor::factory()->create();

    $this->livewire(EditVendedor::class, ['record' => $registro->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($registro->refresh()->activo)->toBeFalse();
});

it('guarda una auditoría cuando cambia un registro de vendedores', function () {
    $registro = Vendedor::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Vendedor::class)->exists())->toBeTrue();
});

it('un usuario sin permiso sobre vendedores no puede ver el listado', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('editor');

    $this->actingAs($editor);

    $this->livewire(ListVendedores::class)->assertForbidden();
});
