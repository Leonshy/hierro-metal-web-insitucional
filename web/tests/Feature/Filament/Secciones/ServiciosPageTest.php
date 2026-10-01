<?php

use App\Filament\Pages\Secciones\ServiciosPage;
use App\Filament\Resources\Servicios\Pages\CreateServicio;
use App\Filament\Resources\Servicios\Pages\EditServicio;
use App\Filament\Resources\Servicios\ServicioResource;
use App\Filament\Secciones\Widgets\PasosTabla;
use App\Filament\Secciones\Widgets\ServiciosTabla;
use App\Models\Page;
use App\Models\Paso;
use App\Models\Servicio;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    Storage::fake('local');
    $this->seed(DatabaseSeeder::class);
    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');
    $this->actingAs($this->admin);
});

it('muestra la sección Servicios con el contenido actual de su página', function () {
    $this->livewire(ServiciosPage::class)
        ->assertSuccessful()
        ->assertFormSet(['titulo' => 'Trabajamos el material por vos', 'boton_texto' => 'Pedir cotización', 'boton_url' => '/contacto']);
});

it('guarda el titular, el botón, el bloque de pasos y el SEO en la página', function () {
    $this->livewire(ServiciosPage::class)
        ->fillForm([
            'titulo' => 'Servicios de taller',
            'bajada' => 'Cortamos y plegamos a tu medida.',
            'boton_texto' => 'Cotizar ahora',
            'boton_url' => '/contacto?rubro=servicio-corte',
            'pasos_titulo' => 'Del plano al camión',
            'pasos_bajada' => 'Siempre los mismos pasos.',
            'seo_titulo' => 'Servicios · Hierro Metal',
            'seo_descripcion' => 'Corte, plegado y entrega.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $pagina = Page::query()->where('slug', 'servicios')->first();

    expect($pagina->getTranslation('title', 'es'))->toBe('Servicios de taller')
        ->and($pagina->getTranslation('seo_title', 'es'))->toBe('Servicios · Hierro Metal')
        ->and($pagina->blocks[0]['data']['cta_url'])->toBe('/contacto?rubro=servicio-corte')
        ->and($pagina->blocks[0]['data']['pasos_titulo'])->toBe(['es' => 'Del plano al camión']);

    $this->get('/servicios')
        ->assertOk()
        ->assertSee('Servicios de taller')
        ->assertSee('Cotizar ahora')
        ->assertSee('Del plano al camión')
        ->assertSee('Siempre los mismos pasos.');
});

it('exige el título', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['titulo' => ''])->call('save')->assertHasFormErrors(['titulo' => 'required']);
});

it('si se borran los textos del bloque de pasos, la página pública vuelve a los de siempre', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['pasos_titulo' => '', 'pasos_bajada' => ''])->call('save');

    $this->get('/servicios')->assertSee('De tu plano a la obra')->assertSee('mismos cuatro pasos');
});

it('lista los servicios y los pasos al pie de la sección', function () {
    $this->livewire(ServiciosTabla::class)->assertSuccessful()->assertCanSeeTableRecords(Servicio::all());
    $this->livewire(PasosTabla::class)->assertSuccessful()->assertCanSeeTableRecords(Paso::all());
});

it('Servicios y Pasos ya no tienen entrada propia en el menú: viven dentro de la sección', function () {
    expect(ServicioResource::shouldRegisterNavigation())->toBeFalse()
        ->and(ServiciosPage::shouldRegisterNavigation())->toBeTrue();
});

it('al crear un servicio se vuelve a la sección', function () {
    $this->livewire(CreateServicio::class)
        ->fillForm(['nombre' => 'Soldadura', 'descripcion' => 'Soldamos estructuras livianas según tu plano.', 'usos' => ['Portones', 'Rejas']])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirect(ServiciosPage::getUrl());
});

it('al editar un servicio se vuelve a la sección', function () {
    $servicio = Servicio::query()->first();

    $this->livewire(EditServicio::class, ['record' => $servicio->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertRedirect(ServiciosPage::getUrl());
});

it('un usuario de ventas no entra a la sección de contenido', function () {
    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');
    $this->actingAs($ventas);

    expect(ServiciosPage::canAccess())->toBeFalse();
});

it('/inicio ya no se sirve como página aparte: redirige a la portada', function () {
    $this->get('/inicio')->assertRedirect('/')->assertStatus(301);
});
