<?php

use App\Filament\Resources\Servicios\Pages\CreateServicio;
use App\Filament\Resources\Servicios\Pages\EditServicio;
use App\Filament\Resources\Servicios\Pages\ListServicios;
use App\Models\Servicio;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista servicios', function () {
    Servicio::factory()->count(3)->create();

    $this->livewire(ListServicios::class)->assertSuccessful();
});

it('crea un registro de servicios y lo deja al final del orden', function () {
    Servicio::factory()->create(['orden' => 4]);

    $this->livewire(CreateServicio::class)
        ->fillForm([
            'nombre' => 'Plegados',
            'descripcion' => 'Doblamos la chapa a los ángulos que necesites, para cerramientos y cubiertas.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Servicio::query()->where('nombre', 'Plegados')->firstOrFail();

    expect($nuevo->orden)->toBe(5);
});

it('edita un registro de servicios', function () {
    $registro = Servicio::factory()->create();

    $this->livewire(EditServicio::class, ['record' => $registro->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($registro->refresh()->activo)->toBeFalse();
});

it('guarda una auditoría cuando cambia un registro de servicios', function () {
    $registro = Servicio::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Servicio::class)->exists())->toBeTrue();
});

it('un usuario sin permiso sobre servicios no puede ver el listado', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('ventas');

    $this->actingAs($editor);

    $this->livewire(ListServicios::class)->assertForbidden();
});
