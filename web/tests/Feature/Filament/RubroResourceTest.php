<?php

use App\Filament\Resources\Rubros\Pages\CreateRubro;
use App\Filament\Resources\Rubros\Pages\EditRubro;
use App\Filament\Resources\Rubros\Pages\ListRubros;
use App\Models\Rubro;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista rubros', function () {
    Rubro::factory()->count(3)->create();

    $this->livewire(ListRubros::class)->assertSuccessful();
});

it('crea un registro de rubros y lo deja al final del orden', function () {
    Rubro::factory()->create(['orden' => 4]);

    $this->livewire(CreateRubro::class)
        ->fillForm([
            'nombre' => 'Galvanización',
            'slug' => 'galvanizacion',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Rubro::query()->where('nombre', 'Galvanización')->firstOrFail();

    expect($nuevo->orden)->toBe(5);
});

it('edita un registro de rubros', function () {
    $registro = Rubro::factory()->create();

    $this->livewire(EditRubro::class, ['record' => $registro->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($registro->refresh()->activo)->toBeFalse();
});

it('guarda una auditoría cuando cambia un registro de rubros', function () {
    $registro = Rubro::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Rubro::class)->exists())->toBeTrue();
});

it('un usuario sin permiso sobre rubros no puede ver el listado', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('ventas');

    $this->actingAs($editor);

    $this->livewire(ListRubros::class)->assertForbidden();
});
