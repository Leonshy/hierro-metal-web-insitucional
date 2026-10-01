<?php

use App\Filament\Resources\Pasos\Pages\CreatePaso;
use App\Filament\Resources\Pasos\Pages\EditPaso;
use App\Filament\Resources\Pasos\Pages\ListPasos;
use App\Models\Paso;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista pasos', function () {
    Paso::factory()->count(3)->create();

    $this->livewire(ListPasos::class)->assertSuccessful();
});

it('crea un registro de pasos y lo deja al final del orden', function () {
    Paso::factory()->create(['orden' => 4]);

    $this->livewire(CreatePaso::class)
        ->fillForm([
            'titulo' => 'Cotizamos',
            'texto' => 'Te pasamos precio, disponibilidad y plazo. En el día.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Paso::query()->where('titulo', 'Cotizamos')->firstOrFail();

    expect($nuevo->orden)->toBe(5);
});

it('edita un registro de pasos', function () {
    $registro = Paso::factory()->create();

    $this->livewire(EditPaso::class, ['record' => $registro->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($registro->refresh()->activo)->toBeFalse();
});

it('guarda una auditoría cuando cambia un registro de pasos', function () {
    $registro = Paso::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Paso::class)->exists())->toBeTrue();
});

it('un usuario sin permiso sobre pasos no puede ver el listado', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('ventas');

    $this->actingAs($editor);

    $this->livewire(ListPasos::class)->assertForbidden();
});
