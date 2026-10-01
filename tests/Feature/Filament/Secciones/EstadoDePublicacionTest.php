<?php

use App\Filament\Pages\Secciones\CalidadPage;
use App\Filament\Pages\Secciones\ContactoPage;
use App\Filament\Pages\Secciones\InicioPage;
use App\Filament\Pages\Secciones\PreguntasFrecuentesPage;
use App\Filament\Pages\Secciones\ProductosPage;
use App\Filament\Pages\Secciones\ServiciosPage;
use App\Filament\Pages\Secciones\UbicacionPage;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
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
    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');
    $this->actingAs($this->admin);
});

/** El sitio público tal como lo ve una persona sin sesión: ahí el borrador no existe. */
function comoVisitante(): void
{
    app('auth')->forgetGuards();
}

function comoAdministrador(): void
{
    test()->actingAs(test()->admin);
}

dataset('secciones con estado', [
    'Productos' => [ProductosPage::class, 'productos', '/productos'],
    'Servicios' => [ServiciosPage::class, 'servicios', '/servicios'],
    'Calidad' => [CalidadPage::class, 'calidad', '/calidad'],
    'Preguntas frecuentes' => [PreguntasFrecuentesPage::class, 'preguntas-frecuentes', '/preguntas-frecuentes'],
    'Ubicación' => [UbicacionPage::class, 'ubicacion', '/ubicacion'],
]);

// ---- El campo de estado ---------------------------------------------------------------------------------------

it('cada sección ofrece elegir Publicada o Borrador, y arranca publicada', function (string $pagina) {
    $this->livewire($pagina)->assertFormFieldExists('estado')->assertFormSet(['estado' => 'published']);
})->with('secciones con estado');

it('la portada y Contacto no tienen estado: siempre se muestran', function (string $pagina) {
    $this->livewire($pagina)->assertFormFieldDoesNotExist('estado');
})->with([[InicioPage::class], [ContactoPage::class]]);

it('Contacto no puede estar en borrador ni siquiera si la base lo dice', function () {
    Page::query()->where('slug', 'contacto')->update(['status' => 'draft']);

    expect(Page::seccionPublicada('contacto'))->toBeTrue()->and(Page::seccionesEnBorrador())->toBe([]);

    comoVisitante();
    $this->get('/contacto')->assertOk();
    $this->get('/sitemap.xml')->assertSee(url('/contacto'), false);
    expect($this->get('/')->getContent())->toContain('href="'.url('/contacto').'"');
});

it('guardar Contacto no cambia su estado', function () {
    $this->livewire(ContactoPage::class)->fillForm(['titulo' => 'Contactanos'])->call('save')->assertHasNoFormErrors();

    expect(Page::query()->where('slug', 'contacto')->value('status'))->toBe('published');
});

it('guardar sin tocar el estado no despublica la página', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['titulo' => 'Otro título'])->call('save');

    expect(Page::query()->where('slug', 'servicios')->value('status'))->toBe('published');
    comoVisitante();
    $this->get('/servicios')->assertOk();
});

it('un borrador conserva el título y los textos que ya se habían cargado', function () {
    $antes = Page::query()->where('slug', 'servicios')->first()->blocks;

    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    expect(Page::query()->where('slug', 'servicios')->first()->blocks[0]['data']['title'])->toBe($antes[0]['data']['title']);
});

// ---- Qué ve el público -----------------------------------------------------------------------------------------

it('al poner una sección en borrador su página pública deja de existir para el público', function (string $pagina, string $slug, string $url) {
    $this->livewire($pagina)->fillForm(['estado' => 'draft'])->call('save')->assertHasNoFormErrors();

    expect(Page::query()->where('slug', $slug)->value('status'))->toBe('draft');
    comoVisitante();
    $this->get($url)->assertNotFound();
})->with('secciones con estado');

it('al volver a publicarla la página vuelve, con todo lo que tenía', function (string $pagina, string $slug, string $url) {
    $this->livewire($pagina)->fillForm(['estado' => 'draft'])->call('save');
    $this->livewire($pagina)->fillForm(['estado' => 'published'])->call('save');

    comoVisitante();
    $this->get($url)->assertOk();
    expect(Page::query()->where('slug', $slug)->value('published_at'))->not->toBeNull();
})->with('secciones con estado');

it('una sección en borrador desaparece del menú y del pie', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    comoVisitante();
    expect($this->get('/')->getContent())->not->toContain('href="'.url('/servicios').'"');
});

it('si Productos está en borrador también se ocultan sus fichas y su enlace en los menús', function () {
    $this->livewire(ProductosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    comoVisitante();
    $this->get('/productos')->assertNotFound();
    $this->get('/productos/chapas')->assertNotFound();
    expect($this->get('/')->getContent())->not->toContain('href="'.url('/productos').'"');
});

it('una sección en borrador no aparece en la portada ni en el sitemap', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    comoVisitante();
    $this->get('/')->assertDontSee('Servicios industriales', false);
    $this->get('/sitemap.xml')->assertOk()->assertDontSee(url('/servicios'), false)->assertSee(url('/productos'), false);
});

it('si Productos está en borrador sus fichas salen del sitemap', function () {
    $this->livewire(ProductosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    comoVisitante();
    $this->get('/sitemap.xml')->assertDontSee(url('/productos/chapas'), false)->assertDontSee(url('/productos'), false);
});

it('con Calidad en borrador la franja de Servicios y el enlace de la portada no apuntan a una página que no existe', function () {
    $this->livewire(CalidadPage::class)->fillForm(['estado' => 'draft'])->call('save');

    comoVisitante();
    $this->get('/servicios')->assertOk()->assertDontSee('Leer la política de calidad');
    $this->get('/')->assertDontSee('Leer la política completa');
});

it('con Productos en borrador los botones «Ver productos» desaparecen', function () {
    $this->livewire(ProductosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    comoVisitante();
    $this->get('/')->assertDontSee('Ver productos y medidas');
    $this->get('/servicios')->assertDontSee('Ver productos');
    $this->get('/pagina-que-no-existe')->assertNotFound()->assertDontSee('Ver productos');
});

it('el estado se lee de la base en cada petición: al publicar de nuevo la lista se actualiza', function () {
    $pagina = Page::query()->where('slug', 'preguntas-frecuentes')->first();

    $pagina->update(['status' => 'draft']);
    expect(Page::seccionesEnBorrador())->toBe(['preguntas-frecuentes']);

    $pagina->update(['status' => 'published']);
    expect(Page::seccionesEnBorrador())->toBe([]);
});

it('las páginas legales siguen teniendo su propio estado en su formulario', function () {
    $this->livewire(CreatePage::class)->assertFormFieldExists('status');
});

// ---- Vista previa ----------------------------------------------------------------------------------------------

it('quien tiene sesión del panel ve el borrador en el sitio, con un aviso, sin indexar y sin caché', function (string $pagina, string $slug, string $url) {
    $this->livewire($pagina)->fillForm(['estado' => 'draft'])->call('save');

    $respuesta = $this->get($url)->assertOk();

    $respuesta->assertSee('Vista previa')->assertSee('Esta página está en borrador')->assertSee('Volver al panel');
    expect($respuesta->getContent())->toContain('<meta name="robots" content="noindex, nofollow">');
    expect($respuesta->headers->get('Cache-Control'))->toContain('no-store')->toContain('private');
})->with('secciones con estado');

it('una página publicada no lleva aviso de vista previa ni se bloquea de la indexación', function () {
    $respuesta = $this->get('/servicios')->assertOk()->assertDontSee('Esta página está en borrador');

    expect($respuesta->getContent())->not->toContain('<meta name="robots" content="noindex');
});

it('el mismo borrador, sin sesión, sigue dando «no encontrada»', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    comoVisitante();
    $this->get('/servicios')->assertNotFound();
});

it('un usuario sin acceso a las páginas (ventas) no ve los borradores', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');
    $this->actingAs($ventas);

    $this->get('/servicios')->assertNotFound();
});

it('un usuario desactivado no ve los borradores', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $inactivo = User::factory()->create(['is_active' => false]);
    $inactivo->assignRole('administrador');
    $this->actingAs($inactivo);

    $this->get('/servicios')->assertNotFound();
});

it('la vista previa muestra la página pero el borrador sigue fuera de la portada, los menús y el sitemap', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    expect($this->get('/')->getContent())->not->toContain('href="'.url('/servicios').'"');
    $this->get('/sitemap.xml')->assertDontSee(url('/servicios'), false);
    $this->get('/')->assertDontSee('Esta página está en borrador'); // la portada no es un borrador: sin aviso
});

it('la vista previa de una sección no filtra a la siguiente petición', function () {
    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $this->get('/servicios')->assertSee('Esta página está en borrador');
    $this->get('/preguntas-frecuentes')->assertOk()->assertDontSee('Esta página está en borrador');
});

it('una página legal en borrador (Privacidad) se ve en vista previa con sesión y no sin ella', function () {
    expect(Page::query()->where('slug', 'privacidad')->value('status'))->toBe('draft');

    $this->get('/privacidad')->assertOk()->assertSee('Esta página está en borrador')->assertSee('Qué datos recopilamos');

    comoVisitante();
    $this->get('/privacidad')->assertNotFound();
});

it('las páginas de las secciones no se sirven por la ruta de páginas libres, ni en vista previa', function () {
    // «inicio» redirige; el resto tiene su propia ruta. Ninguna se puede espiar por /{slug} como borrador.
    $this->get('/inicio')->assertRedirect('/');
});

it('el botón del panel dice «Vista previa» si la página está en borrador y «Ver en el sitio» si está publicada', function () {
    $this->livewire(ServiciosPage::class)->assertActionExists('ver')->assertActionHasLabel('ver', 'Ver en el sitio');

    $this->livewire(ServiciosPage::class)->fillForm(['estado' => 'draft'])->call('save');

    $this->livewire(ServiciosPage::class)->assertActionHasLabel('ver', 'Vista previa');
});

it('la portada y Contacto tienen «Ver en el sitio»', function () {
    $this->livewire(InicioPage::class)->assertActionHasLabel('ver', 'Ver en el sitio')->assertActionHasUrl('ver', url('/'));
    $this->livewire(ContactoPage::class)->assertActionHasLabel('ver', 'Ver en el sitio')->assertActionHasUrl('ver', url('/contacto'));
});

it('las páginas legales también tienen el botón de vista previa al editarlas', function () {
    $privacidad = Page::query()->where('slug', 'privacidad')->first();

    $this->livewire(EditPage::class, ['record' => $privacidad->getRouteKey()])
        ->assertActionHasLabel('ver', 'Vista previa')
        ->assertActionHasUrl('ver', url('/privacidad'));
});
