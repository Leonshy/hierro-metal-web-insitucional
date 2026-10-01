<?php

use App\Models\Compromiso;
use App\Models\Faq;
use App\Models\Page;
use App\Models\SiteSetting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

it('muestra la política de calidad con la introducción antes que los compromisos', function () {
    $html = $this->get('/calidad')->assertOk()->getContent();

    expect($html)->toContain('Materia prima certificada bajo Normas Internacionales del Acero')
        ->and($html)->toContain('Control de calidad estricto')
        ->and($html)->toContain('Qué significa esto para tu obra')
        ->and($html)->toContain('Ámbito de aplicación');

    // Orden pedido: introducción → compromisos → resto del texto.
    expect(strpos($html, 'importamos y comercializamos'))->toBeLessThan(strpos($html, 'Nuestros compromisos'))
        ->and(strpos($html, 'Nuestros compromisos'))->toBeLessThan(strpos($html, 'Qué significa esto para tu obra'));
});

it('responde 404 en /calidad si la página no está publicada', function () {
    Page::query()->where('slug', 'calidad')->update(['status' => 'draft']);

    $this->get('/calidad')->assertNotFound();
});

it('no muestra compromisos ocultos', function () {
    Compromiso::query()->where('titulo', 'Mejora continua')->update(['activo' => false]);

    $this->get('/calidad')->assertOk()->assertDontSee('Mejora continua');
});

it('muestra las preguntas frecuentes con sus respuestas y los datos estructurados', function () {
    $this->get('/preguntas-frecuentes')
        ->assertOk()
        ->assertSee('Lo que más nos preguntan')
        ->assertSee('¿Venden al por mayor y al por menor?')
        ->assertSee('"@type":"FAQPage"', false)
        ->assertSee('/servicios', false);
});

it('no muestra preguntas ocultas ni en la página ni en los datos estructurados', function () {
    Faq::query()->where('pregunta', '¿Ofrecen galvanización?')->update(['activo' => false]);

    $this->get('/preguntas-frecuentes')->assertOk()->assertDontSee('¿Ofrecen galvanización?');
});

it('muestra la ubicación con dirección, horarios y el mapa sin cargarlo', function () {
    $respuesta = $this->get('/ubicacion')
        ->assertOk()
        ->assertSee('Casa matriz en Fernando de la Mora')
        ->assertSee('Pedro Getto')
        ->assertSee('Horario de atención')
        ->assertSee('Cargar mapa')
        ->assertSee('Abrir en Google Maps');

    // El iframe de Google va dentro de un <template> de Alpine: no se pide hasta que la persona lo elige.
    $html = $respuesta->getContent();
    $posIframe = strpos($html, '<iframe');
    expect($posIframe)->not->toBeFalse()
        ->and(strrpos(substr($html, 0, $posIframe), '<template'))->not->toBeFalse();
});

it('muestra la página 404 con salidas claras, sin textos de otro proyecto', function () {
    $this->get('/esta-pagina-no-existe')
        ->assertNotFound()
        ->assertSee('No encontramos esa página')
        ->assertSee('Ver productos')
        ->assertSee('Escribir por WhatsApp')
        ->assertDontSee('secretaría');
});

it('mantiene Privacidad sin publicar hasta la revisión legal', function () {
    expect(Page::query()->where('slug', 'privacidad')->value('status'))->toBe('draft');

    $this->get('/privacidad')->assertNotFound();
});

it('muestra una página de texto publicada con el encabezado y los datos estructurados de migas', function () {
    Page::query()->where('slug', 'privacidad')->update(['status' => 'published', 'published_at' => now()]);

    $this->get('/privacidad')
        ->assertOk()
        ->assertSee('<div class="prosa">', false)
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertSee('Escribir por WhatsApp');
});

it('la página de mantenimiento habla en la voz del sitio y sin referencias a otro proyecto', function () {
    SiteSetting::set('maintenance_mode', true, 'boolean');

    $this->get('/')
        ->assertStatus(503)
        ->assertSee('El sitio está en mantenimiento')
        ->assertSee('Volvé a intentarlo')
        ->assertDontSee('colegio');
});

it('los encabezados de sección no repiten el nombre de la sección sobre el título', function () {
    foreach (['/servicios', '/calidad', '/preguntas-frecuentes', '/ubicacion', '/contacto'] as $ruta) {
        $html = $this->get($ruta)->assertOk()->getContent();
        $encabezado = substr($html, strpos($html, '<section class="encabezado-pagina'), 900);

        expect($encabezado)->not->toContain('class="rotulo"', "Rótulo redundante en {$ruta}")
            ->and($encabezado)->toContain('<h1>');
    }
});

it('las migas de pan siguen estando encima del título en las secciones', function () {
    foreach (['/servicios', '/calidad', '/preguntas-frecuentes', '/ubicacion'] as $ruta) {
        $html = $this->get($ruta)->getContent();

        expect(strpos($html, 'class="migas"'))->toBeLessThan(strpos($html, '<h1>'));
    }
});
