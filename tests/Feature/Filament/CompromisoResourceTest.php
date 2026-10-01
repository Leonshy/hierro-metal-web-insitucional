<?php

use App\Filament\Resources\Compromisos\Pages\CreateCompromiso;
use App\Filament\Resources\Compromisos\Pages\EditCompromiso;
use App\Filament\Resources\Compromisos\Pages\ListCompromisos;
use App\Models\Compromiso;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista compromisos', function () {
    Compromiso::factory()->count(3)->create();

    $this->livewire(ListCompromisos::class)->assertSuccessful();
});

it('crea un registro de compromisos y lo deja al final del orden', function () {
    Compromiso::factory()->create(['orden' => 4]);

    $this->livewire(CreateCompromiso::class)
        ->fillForm([
            'titulo' => 'Control de calidad estricto',
            'texto' => 'Controlamos el material al recibirlo.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Compromiso::query()->where('titulo', 'Control de calidad estricto')->firstOrFail();

    expect($nuevo->orden)->toBe(5);
});

it('edita un registro de compromisos', function () {
    $registro = Compromiso::factory()->create();

    $this->livewire(EditCompromiso::class, ['record' => $registro->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($registro->refresh()->activo)->toBeFalse();
});

it('guarda una auditoría cuando cambia un registro de compromisos', function () {
    $registro = Compromiso::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Compromiso::class)->exists())->toBeTrue();
});

it('un usuario sin permiso sobre compromisos no puede ver el listado', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('ventas');

    $this->actingAs($editor);

    $this->livewire(ListCompromisos::class)->assertForbidden();
});
