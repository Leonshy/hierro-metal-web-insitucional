<?php

use App\Filament\Pages\Secciones\CalidadPage;
use App\Filament\Pages\Secciones\ContactoPage;
use App\Filament\Pages\Secciones\InicioPage;
use App\Filament\Pages\Secciones\PreguntasFrecuentesPage;
use App\Filament\Pages\Secciones\ProductosPage;
use App\Filament\Pages\Secciones\ServiciosPage;
use App\Filament\Pages\Secciones\UbicacionPage;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    Storage::fake('local');
    config(['sitio.seo.block_indexing' => false]);
    $this->seed(DatabaseSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

dataset('secciones con estado', [
    'Productos' => [ProductosPage::class, 'productos', '/productos'],
    'Servicios' => [ServiciosPage::class, 'servicios', '/servicios'],
    'Calidad' => [CalidadPage::class, 'calidad', '/calidad'],
    'Preguntas frecuentes' => [PreguntasFrecuentesPage::class, 'preguntas-frecuentes', '/preguntas-frecuentes'],
    'Ubicación' => [UbicacionPage::class, 'ubicacion', '/ubicacion'],
    'Contacto' => [ContactoPage::class, 'contacto', '/contacto'],
]);

it('cada sección ofrece elegir Publicada o Borrador, y arranca publicada', function (string $pagina) {
    $this->livewire($pagina)->assertFormFieldExists('estado')->assertFormSet(['estado' => 'published']);
})->with('secciones con estado');

it('la portada no tiene estado: siempre se muestra', function () {
    $this->livewire(InicioPage::class)->assertFormFieldDoesNotExist('estado');
});

it('al poner una sección en borrador su página pública deja de existir', function (string $pagina, string $slug, string $url) {
    $this->get($url)->assertOk();

    $this->livewire($pagina)->fillForm(['estado' => 'draft'])->call('save')->assertHasNoFormErrors();

    expect(Page::query()->where('slug', $slug)->value('status'))->toBe('draft');
    $this->get($url)->assertNotFound();
})->with('secciones con estado');

it('al volver a publicarla la página vuelve, con todo lo que tenía', function (string $pagina, string $slug, string $url) {
    $this->livewire($pagina)->fillForm(['estado' => 'draft'])->call('save');
    $this->get($url)->assertNotFound();

    $this->livewire($pagina)->fillForm(['estado' => 'published'])->call('save');

    $this->get($url)->assertOk();
    expect(Page::query()->where('slug', $slug)->value('published_at'))->not->toBeNull();
})->with('secciones con estado');

it('un borrador conserva el título y los textos que ya se habían cargado', function () {
    $antes = Page::query()->where('slug', 'servicios')->first()->blocks;

    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    expect(Page::query()->where('slug', 'servicios')->first()->blocks[0]['data']['title'])->toBe($antes[0]['data']['title']);
});

it('una sección en borrador desaparece del menú y del pie', function () {
    $this->get('/')->assertSee('Servicios industriales')->assertSee('/servicios', false);

    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $html = $this->get('/')->getContent();
    expect($html)->not->toContain('href="'.url('/servicios').'"');
});

it('si Productos está en borrador también se ocultan sus fichas y su enlace en los menús', function () {
    $this->livewire(ProductosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $this->get('/productos')->assertNotFound();
    $this->get('/productos/chapas')->assertNotFound();
    expect($this->get('/')->getContent())->not->toContain('href="'.url('/productos').'"');
});

it('una sección en borrador no aparece en la portada ni en el sitemap', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $this->get('/')->assertDontSee('Servicios industriales', false);
    $this->get('/sitemap.xml')->assertOk()->assertDontSee(url('/servicios'), false)->assertSee(url('/productos'), false);
});

it('si Contacto está en borrador sale del sitemap, y si Productos lo está también sus fichas', function () {
    $this->livewire(ContactoPage::class)->fillForm(['estado' => 'draft'])->call('save');
    $this->livewire(ProductosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $this->get('/sitemap.xml')->assertDontSee(url('/contacto'), false)->assertDontSee(url('/productos/chapas'), false);
});

it('con Calidad en borrador la franja de Servicios y el enlace de la portada no apuntan a una página que no existe', function () {
    $this->livewire(CalidadPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $this->get('/servicios')->assertOk()->assertDontSee('Leer la política de calidad');
    $this->get('/')->assertDontSee('Leer la política completa');
});

it('con Productos en borrador los botones «Ver productos» desaparecen', function () {
    $this->livewire(ProductosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $this->get('/')->assertDontSee('Ver productos y medidas');
    $this->get('/servicios')->assertDontSee('Ver productos');
    $this->get('/pagina-que-no-existe')->assertNotFound()->assertDontSee('Ver productos');
});

it('guardar sin tocar el estado no despublica la página', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['titulo' => 'Otro título'])->call('save');

    expect(Page::query()->where('slug', 'servicios')->value('status'))->toBe('published');
    $this->get('/servicios')->assertOk();
});

it('el estado se lee de la base en cada petición: al publicar de nuevo la lista se actualiza', function () {
    Page::query()->where('slug', 'faq')->update(['status' => 'draft']);
    $pagina = Page::query()->where('slug', 'preguntas-frecuentes')->first();

    $pagina->update(['status' => 'draft']);
    expect(Page::seccionesEnBorrador())->toBe(['preguntas-frecuentes']);

    $pagina->update(['status' => 'published']);
    expect(Page::seccionesEnBorrador())->toBe([]);
});

it('las páginas legales siguen teniendo su propio estado en su formulario', function () {
    $this->livewire(CreatePage::class)->assertFormFieldExists('status');
});
