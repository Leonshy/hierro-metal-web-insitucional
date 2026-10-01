<?php

use App\Filament\Resources\Diferenciales\Pages\CreateDiferencial;
use App\Filament\Resources\Diferenciales\Pages\EditDiferencial;
use App\Filament\Resources\Diferenciales\Pages\ListDiferenciales;
use App\Models\Diferencial;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista diferenciales', function () {
    Diferencial::factory()->count(3)->create();

    $this->livewire(ListDiferenciales::class)->assertSuccessful();
});

it('crea un registro de diferenciales y lo deja al final del orden', function () {
    Diferencial::factory()->create(['orden' => 4]);

    $this->livewire(CreateDiferencial::class)
        ->fillForm([
            'titulo' => 'Flota propia',
            'texto' => 'Entrega de materiales en obra.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Diferencial::query()->where('titulo', 'Flota propia')->firstOrFail();

    expect($nuevo->orden)->toBe(5);
});

it('edita un registro de diferenciales', function () {
    $registro = Diferencial::factory()->create();

    $this->livewire(EditDiferencial::class, ['record' => $registro->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($registro->refresh()->activo)->toBeFalse();
});

it('guarda una auditoría cuando cambia un registro de diferenciales', function () {
    $registro = Diferencial::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Diferencial::class)->exists())->toBeTrue();
});

it('un usuario sin permiso sobre diferenciales no puede ver el listado', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('ventas');

    $this->actingAs($editor);

    $this->livewire(ListDiferenciales::class)->assertForbidden();
});
