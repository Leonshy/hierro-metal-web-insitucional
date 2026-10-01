<?php

use App\Models\Familia;
use App\Models\Linea;
use App\Models\Page;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

it('el sitemap incluye páginas publicadas e indexables, con lastmod real', function () {
    config(['sitio.seo.block_indexing' => false]);

    $page = Page::factory()->create([
        'slug' => 'institucion/historia',
        'status' => 'published',
        'is_indexable' => true,
    ]);

    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    $response->assertSee(url('/institucion/historia'), false);
    $response->assertSee('<lastmod>'.$page->updated_at->format('Y-m-d'), false);
});

it('el sitemap excluye borradores y páginas noindex', function () {
    $draft = Page::factory()->create(['slug' => 'borrador', 'status' => 'draft']);
    $noindex = Page::factory()->create(['slug' => 'no-indexable', 'status' => 'published', 'is_indexable' => false]);

    $response = $this->get('/sitemap.xml');

    $response->assertDontSee(url('/'.$draft->slug), false);
    $response->assertDontSee(url('/'.$noindex->slug), false);
});

it('robots.txt permite todo y referencia el sitemap cuando la indexación no está bloqueada', function () {
    config(['sitio.seo.block_indexing' => false]);

    $response = $this->get('/robots.txt');

    $response->assertOk();
    $response->assertSee('Disallow:');
    $response->assertDontSee('Disallow: /', false);
    $response->assertSee('Sitemap: '.route('sitemap.index'));
});

it('robots.txt bloquea todo en un entorno que no es de producción', function () {
    config(['sitio.seo.block_indexing' => true]);

    $response = $this->get('/robots.txt');

    $response->assertOk()->assertSee('Disallow: /');
});

it('el sitemap incluye las fichas de producto de las familias visibles', function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
    Familia::query()->where('slug', 'accesorios')->update(['activo' => false]);

    $respuesta = $this->get('/sitemap.xml')->assertOk();

    foreach (['chapas', 'perfiles', 'tubos', 'varillas'] as $slug) {
        $respuesta->assertSee(url('/productos/'.$slug), false);
    }
    $respuesta->assertDontSee(url('/productos/accesorios'), false);
});

it('al editar una familia o una línea cambia la fecha de modificación de sus páginas', function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);

    $this->travelTo(now()->addDays(10));
    Linea::query()->where('familia_id', Familia::where('slug', 'tubos')->value('id'))->first()->touch();
    $esperada = now()->format('Y-m-d');

    $xml = $this->get('/sitemap.xml')->getContent();

    foreach (['/productos/tubos', '/productos'] as $ruta) {
        $bloque = substr($xml, strpos($xml, '<loc>'.url($ruta).'</loc>'), 200);
        expect($bloque)->toContain('<lastmod>'.$esperada);
    }
});

it('el sitemap no repite direcciones', function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);

    preg_match_all('#<loc>([^<]+)</loc>#', $this->get('/sitemap.xml')->getContent(), $m);

    expect($m[1])->toBe(array_values(array_unique($m[1])));
});
