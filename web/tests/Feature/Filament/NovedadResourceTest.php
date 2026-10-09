<?php

use App\Filament\Pages\Secciones\NovedadesPage;
use App\Filament\Resources\Novedades\Pages\CreateNovedad;
use App\Filament\Resources\Novedades\Pages\EditNovedad;
use App\Filament\Secciones\Widgets\NovedadesTabla;
use App\Models\Novedad;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('media');
    $this->seed(DatabaseSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('abre la pantalla de la sección con la tabla de novedades', function () {
    Novedad::factory()->count(2)->create();

    Livewire::test(NovedadesPage::class)->assertSuccessful()->assertSchemaStateSet(['titulo' => 'Novedades']);
    Livewire::test(NovedadesTabla::class)->assertSuccessful()->assertCanSeeTableRecords(Novedad::all());
});

it('crea una novedad y se ve en el sitio', function () {
    Livewire::test(CreateNovedad::class)
        ->fillForm([
            'titulo' => 'Nuevo servicio de plegado',
            'slug' => 'nuevo-servicio-de-plegado',
            'resumen' => 'Sumamos una plegadora de 3 metros.',
            'contenido' => '<p>Plegamos hasta 3 metros.</p>',
            'publicada_en' => now()->subMinute(),
            'activo' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->get('/novedades/nuevo-servicio-de-plegado')->assertOk()->assertSee('Plegamos hasta 3 metros');
});

it('rechaza una dirección con mayúsculas o repetida', function () {
    Novedad::factory()->create(['slug' => 'ya-existe']);

    Livewire::test(CreateNovedad::class)
        ->fillForm(['titulo' => 'X', 'slug' => 'Mala Direccion', 'resumen' => 'r', 'contenido' => '<p>c</p>'])
        ->call('create')
        ->assertHasFormErrors(['slug']);

    Livewire::test(CreateNovedad::class)
        ->fillForm(['titulo' => 'X', 'slug' => 'ya-existe', 'resumen' => 'r', 'contenido' => '<p>c</p>'])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

it('edita una novedad y la puede esconder', function () {
    $novedad = Novedad::factory()->create();

    Livewire::test(EditNovedad::class, ['record' => $novedad->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($novedad->refresh()->activo)->toBeFalse();

    // Quien tiene sesión del panel ve la sección en vista previa; el público ya no la ve.
    app('auth')->forgetGuards();
    $this->get('/novedades')->assertNotFound();
});

it('el rol editor administra novedades; el de ventas no', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('editor');
    expect($editor->can('novedades.create'))->toBeTrue()->and($editor->can('popups.update'))->toBeTrue();

    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');
    expect($ventas->can('novedades.view'))->toBeFalse()->and($ventas->can('popups.view'))->toBeFalse();
});
