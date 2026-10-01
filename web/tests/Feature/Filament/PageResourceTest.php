<?php

use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Media;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');

    $this->actingAs($this->admin);
});

it('lista las páginas en el panel', function () {
    Page::factory()->count(2)->create();

    $this->livewire(ListPages::class)
        ->assertSuccessful();
});

it('muestra el enlace público completo de una página, incluida la ruta anidada del padre', function () {
    $parent = Page::factory()->create(['slug' => 'institucion']);
    $child = Page::factory()->create(['slug' => 'historia', 'parent_id' => $parent->id]);

    $this->livewire(ListPages::class)
        ->assertTableColumnStateSet('public_url', url('institucion/historia'), record: $child);
});

it('crea una página con título en español y bloque de texto', function () {
    $this->livewire(CreatePage::class)
        ->fillForm([
            'title' => ['es' => 'Historia'],
            'slug' => 'institucion/historia',
            'site_section' => 'institucion',
            'status' => 'draft',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Page::query()->where('slug', 'institucion/historia')->exists())->toBeTrue();
});

it('exige el título en español', function () {
    $this->livewire(CreatePage::class)
        ->fillForm([
            'title' => ['es' => ''],
            'slug' => 'sin-titulo',
            'site_section' => 'general',
        ])
        ->call('create')
        ->assertHasFormErrors(['title.es' => 'required']);
});

it('sanitiza el HTML del bloque de texto antes de guardar', function () {
    $page = Page::factory()->create();

    $this->livewire(EditPage::class, ['record' => $page->getRouteKey()])
        ->fillForm([
            'blocks' => [
                'bloque-1' => [
                    'type' => 'texto',
                    'data' => [
                        'content' => ['es' => '<p>Hola</p><script>alert(1)</script>'],
                    ],
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $page->refresh();

    expect($page->blocks[0]['data']['content']['es'])
        ->toContain('<p>Hola</p>')
        ->not->toContain('<script>');
});

it('bloquea imágenes de dominios externos en el bloque de texto', function () {
    $page = Page::factory()->create();

    $this->livewire(EditPage::class, ['record' => $page->getRouteKey()])
        ->fillForm([
            'blocks' => [
                'bloque-1' => [
                    'type' => 'texto',
                    'data' => [
                        'content' => ['es' => '<p>Hola</p><img src="http://evil.example.com/x.png">'],
                    ],
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $page->refresh();

    expect($page->blocks[0]['data']['content']['es'])->not->toContain('evil.example.com');
});

it('un usuario sin permiso no puede ver el listado de páginas', function () {
    $usuarioVentas = User::factory()->create(['is_active' => true]);
    $usuarioVentas->assignRole('ventas');

    $this->actingAs($usuarioVentas);

    $this->livewire(ListPages::class)->assertForbidden();
});

it('editor ve y edita páginas pero no puede borrarlas (permiso real, no solo el botón oculto)', function () {
    $editorGeneral = User::factory()->create(['is_active' => true]);
    $editorGeneral->assignRole('editor');
    $page = Page::factory()->create();

    expect($editorGeneral->can('viewAny', Page::class))->toBeTrue()
        ->and($editorGeneral->can('update', $page))->toBeTrue()
        ->and($editorGeneral->can('delete', $page))->toBeFalse();
});

it('elige la imagen para compartir (SEO) de la biblioteca de medios desde el picker', function () {
    $seoImage = Media::factory()->create();

    $this->livewire(CreatePage::class)
        ->fillForm([
            'title' => ['es' => 'Términos y condiciones'],
            'slug' => 'terminos-y-condiciones',
            'status' => 'draft',
            'seo_image_id' => $seoImage->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $page = Page::query()->where('slug', 'terminos-y-condiciones')->firstOrFail();

    expect($page->seo_image_id)->toBe($seoImage->id)
        ->and($page->site_section)->toBe('general'); // ya no se elige: es una página legal, sin sección de menú
});

it('una página legal no ofrece portada, sección de menú ni página padre', function () {
    $this->livewire(CreatePage::class)
        ->assertFormFieldDoesNotExist('cover_media_id')
        ->assertFormFieldDoesNotExist('site_section')
        ->assertFormFieldDoesNotExist('parent_id');
});

it('una página legal no puede usar la dirección de una sección del sitio ni del propio sistema', function () {
    foreach (['servicios', 'productos', 'inicio', 'contacto/gracias', 'panel/algo', 'productos/mi-pagina', 'storage'] as $reservada) {
        $this->livewire(CreatePage::class)
            ->fillForm(['title' => ['es' => 'Prueba'], 'slug' => $reservada, 'status' => 'draft'])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }
});

it('una dirección libre sí se acepta, también con subcarpetas', function () {
    $this->livewire(CreatePage::class)
        ->fillForm(['title' => ['es' => 'Cookies'], 'slug' => 'legal/politica-de-cookies', 'status' => 'draft'])
        ->call('create')
        ->assertHasNoFormErrors();
});

it('las páginas de las secciones del sitio no aparecen en Páginas legales ni se pueden abrir desde ahí', function () {
    Storage::fake('media');
    Storage::fake('public');
    Storage::fake('local');
    $this->seed(DatabaseSeeder::class);

    $estructural = Page::query()->where('slug', 'servicios')->first();
    $legal = Page::query()->where('slug', 'privacidad')->first();

    $this->livewire(ListPages::class)
        ->assertCanSeeTableRecords([$legal])
        ->assertCanNotSeeTableRecords(Page::query()->whereIn('slug', Page::SECCIONES)->get());

    $this->get(PageResource::getUrl('edit', ['record' => $estructural]))->assertNotFound();
});

it('el menú dice Páginas legales', function () {
    expect(PageResource::getNavigationLabel())->toBe('Páginas legales')
        ->and(PageResource::getPluralModelLabel())->toBe('páginas legales');
});
