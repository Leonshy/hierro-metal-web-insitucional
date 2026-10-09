<?php

use App\Filament\Resources\Popups\Pages\CreatePopup;
use App\Filament\Resources\Popups\Pages\EditPopup;
use App\Filament\Resources\Popups\Pages\ListPopups;
use App\Models\Media;
use App\Models\Popup;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista los pop-ups', function () {
    Popup::factory()->count(3)->create();

    Livewire::test(ListPopups::class)->assertSuccessful()->assertCanSeeTableRecords(Popup::all());
});

it('crea un pop-up con imagen y enlace, y queda al final del orden', function () {
    Popup::factory()->create(['orden' => 4]);
    $media = Media::factory()->create();

    Livewire::test(CreatePopup::class)
        ->fillForm(['nombre' => 'Promo de octubre', 'media_id' => $media->id, 'enlace' => '/productos/chapas', 'donde' => 'inicio', 'frecuencia' => 'dia'])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Popup::query()->where('nombre', 'Promo de octubre')->firstOrFail();

    expect($nuevo->orden)->toBe(5)->and($nuevo->activo)->toBeTrue();
});

it('la imagen es obligatoria', function () {
    Livewire::test(CreatePopup::class)
        ->fillForm(['nombre' => 'Sin imagen', 'donde' => 'inicio', 'frecuencia' => 'sesion'])
        ->call('create')
        ->assertHasFormErrors(['media_id' => 'required']);
});

it('sólo acepta rutas del sitio o direcciones https en el enlace', function (string $enlace, bool $valido) {
    $media = Media::factory()->create();

    $prueba = Livewire::test(CreatePopup::class)
        ->fillForm(['nombre' => 'P', 'media_id' => $media->id, 'enlace' => $enlace, 'donde' => 'inicio', 'frecuencia' => 'sesion'])
        ->call('create');

    $valido ? $prueba->assertHasNoFormErrors() : $prueba->assertHasFormErrors(['enlace']);
})->with([
    'ruta del sitio' => ['/contacto', true],
    'https' => ['https://example.com/promo', true],
    'http' => ['http://example.com', true],
    'javascript' => ['javascript:alert(1)', false],
    'sin protocolo' => ['example.com', false],
    'protocolo relativo' => ['//example.com', false],
    'con espacios' => ['/contacto ahora', false],
]);

it('la fecha de fin no puede ser anterior a la de inicio', function () {
    $media = Media::factory()->create();

    Livewire::test(CreatePopup::class)
        ->fillForm(['nombre' => 'P', 'media_id' => $media->id, 'donde' => 'inicio', 'frecuencia' => 'sesion', 'desde' => now()->addDays(3), 'hasta' => now()])
        ->call('create')
        ->assertHasFormErrors(['hasta']);
});

it('edita y apaga un pop-up', function () {
    $popup = Popup::factory()->create();

    Livewire::test(EditPopup::class, ['record' => $popup->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($popup->refresh()->activo)->toBeFalse();
});

it('un usuario sin permiso sobre pop-ups no ve el listado', function () {
    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');

    $this->actingAs($ventas);

    Livewire::test(ListPopups::class)->assertForbidden();
});
