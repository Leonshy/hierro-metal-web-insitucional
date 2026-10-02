<?php

use App\Models\Page;
use Database\Seeders\DatabaseSeeder;

/** El pie lista, una al lado de otra, cada página legal publicada: las «libres», no las secciones del sitio. */
beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

function legalesDelPie(string $html): array
{
    preg_match('#<nav class="pie-legales"[^>]*>(.*?)</nav>#s', $html, $bloque);
    preg_match_all('#<a href="[^"]*/([^"/]+)">([^<]+)</a>#', $bloque[1] ?? '', $enlaces, PREG_SET_ORDER);

    return array_map(fn (array $e): string => $e[1].'='.trim($e[2]), $enlaces);
}

it('no muestra la política de privacidad en el pie mientras está en borrador', function () {
    expect(Page::query()->where('slug', 'privacidad')->value('status'))->toBe('draft');

    $this->get('/')->assertOk()->assertDontSee('pie-legales', false);
});

it('al publicar la política de privacidad aparece en el pie de todas las páginas', function () {
    Page::query()->where('slug', 'privacidad')->firstOrFail()->update(['status' => 'published']);

    foreach (['/', '/productos', '/contacto'] as $ruta) {
        expect(legalesDelPie($this->get($ruta)->getContent()))->toBe(['privacidad=Política de privacidad']);
    }
});

it('cada página legal nueva que se publica se suma al pie, en el orden en que se creó', function () {
    Page::query()->where('slug', 'privacidad')->firstOrFail()->update(['status' => 'published']);
    $this->get('/')->assertOk(); // deja la lista en caché: publicar otra página debe renovarla

    Page::factory()->create(['slug' => 'terminos-y-condiciones', 'title' => ['es' => 'Términos y condiciones'], 'status' => 'published']);
    Page::factory()->create(['slug' => 'devoluciones', 'title' => ['es' => 'Política de devoluciones'], 'status' => 'published']);

    expect(legalesDelPie($this->get('/')->getContent()))->toBe([
        'privacidad=Política de privacidad',
        'terminos-y-condiciones=Términos y condiciones',
        'devoluciones=Política de devoluciones',
    ]);
});

it('una página legal en borrador, o que se vuelve a borrador, o que se borra, sale del pie', function () {
    $terminos = Page::factory()->create(['slug' => 'terminos', 'title' => ['es' => 'Términos'], 'status' => 'published']);
    Page::factory()->create(['slug' => 'borrador-legal', 'title' => ['es' => 'En borrador'], 'status' => 'draft']);

    expect(legalesDelPie($this->get('/')->getContent()))->toBe(['terminos=Términos']);

    $terminos->update(['status' => 'draft']);
    $this->get('/')->assertDontSee('pie-legales', false);

    $terminos->update(['status' => 'published']);
    expect(legalesDelPie($this->get('/')->getContent()))->toBe(['terminos=Términos']);

    $terminos->delete();
    $this->get('/')->assertDontSee('pie-legales', false);
});

it('las secciones del sitio nunca aparecen como páginas legales del pie', function () {
    expect(Page::enlacesLegales())->toBe([]);

    $html = $this->get('/')->getContent();

    expect($html)->not->toContain('pie-legales');
});

it('el enlace del pie lleva a una página que abre', function () {
    Page::factory()->create(['slug' => 'terminos', 'title' => ['es' => 'Términos y condiciones'], 'status' => 'published']);

    $this->get('/terminos')->assertOk();
    $this->get('/')->assertSee('href="'.url('/terminos').'"', false);
});
