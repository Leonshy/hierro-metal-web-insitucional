<?php

use App\Models\Familia;
use App\Models\Media;
use App\Support\Encabezado;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

dataset('paginas publicas', [
    '/', '/productos', '/productos/chapas', '/productos/perfiles', '/productos/tubos', '/productos/varillas',
    '/productos/accesorios', '/servicios', '/calidad', '/preguntas-frecuentes', '/ubicacion', '/contacto',
]);

it('cada página pública cumple los límites de SEO y tiene un solo h1', function (string $ruta) {
    $html = $this->get($ruta)->assertOk()->getContent();

    preg_match('#<title>([^<]+)</title>#', $html, $titulo);
    preg_match('#<meta name="description" content="([^"]*)"#', $html, $descripcion);

    expect(mb_strlen(html_entity_decode($titulo[1])))->toBeLessThanOrEqual(60)
        ->and(mb_strlen(html_entity_decode($descripcion[1] ?? '')))->toBeGreaterThan(40)->toBeLessThanOrEqual(160)
        ->and(substr_count($html, '<h1'))->toBe(1)
        ->and($html)->toContain('<link rel="canonical"')
        ->and($html)->toContain('lang="es"');
})->with('paginas publicas');

it('cada página pública comparte una imagen con URL absoluta', function (string $ruta) {
    $html = $this->get($ruta)->getContent();

    preg_match('#<meta property="og:image" content="([^"]+)"#', $html, $imagen);

    expect($imagen)->not->toBeEmpty()
        ->and($imagen[1])->toStartWith('http')
        ->and($html)->toContain('summary_large_image');
})->with('paginas publicas');

it('la ficha de una familia comparte la foto de esa familia', function () {
    $familia = Familia::query()->with('media')->where('slug', 'tubos')->first();

    $this->get('/productos/tubos')
        ->assertOk()
        ->assertSee('<meta property="og:image" content="'.url($familia->media->conversionUrl('large') ?? $familia->media->url()).'"', false);
});

it('la portada comparte la foto de portada y el resto usa la imagen por defecto', function () {
    $foto = Media::query()->find(Encabezado::de('inicio')->mediaId);
    $this->get('/')->assertSee('<meta property="og:image" content="'.url($foto->conversionUrl('large') ?? $foto->url()).'"', false);
    $this->get('/servicios')->assertSee('images/og-hierro-metal.jpg', false);
});

it('la imagen por defecto existe y tiene la proporción recomendada para redes', function () {
    [$ancho, $alto] = getimagesize(public_path('images/og-hierro-metal.jpg'));

    expect($ancho)->toBeGreaterThanOrEqual(600)
        ->and(round($ancho / $alto, 1))->toBe(1.9);
});

it('la página de gracias no se indexa y no tiene descripción para buscadores', function () {
    $html = $this->get('/contacto/gracias')->assertOk()->getContent();

    expect($html)->toContain('<meta name="robots" content="noindex, nofollow">');
});

it('todos los datos estructurados de cada página son JSON válido con su @context', function (string $ruta) {
    $html = $this->get($ruta)->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $bloques);

    expect($bloques[1])->not->toBeEmpty();

    foreach ($bloques[1] as $json) {
        $dato = json_decode($json, true);

        expect(json_last_error())->toBe(JSON_ERROR_NONE, 'JSON-LD inválido: '.substr($json, 0, 120))
            ->and($dato['@context'])->toBe('https://schema.org')
            ->and($dato['@type'])->toBeString();
    }
})->with('paginas publicas');

it('las imágenes y direcciones de los datos estructurados son absolutas', function () {
    $html = $this->get('/productos/chapas')->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $bloques);
    $negocio = collect($bloques[1])->map(fn ($j) => json_decode($j, true))->firstWhere('@type', 'HardwareStore');

    expect($negocio['image'])->toStartWith('http')
        ->and($negocio['logo'])->toStartWith('http')
        ->and($negocio['url'])->toStartWith('http');
});

it('las páginas con migas publican BreadcrumbList en orden y con enlaces absolutos', function () {
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $this->get('/productos/chapas')->getContent(), $bloques);
    $migas = collect($bloques[1])->map(fn ($j) => json_decode($j, true))->firstWhere('@type', 'BreadcrumbList');

    expect(collect($migas['itemListElement'])->pluck('name')->all())->toBe(['Inicio', 'Productos', 'Chapas de acero'])
        ->and(collect($migas['itemListElement'])->pluck('position')->all())->toBe([1, 2, 3])
        ->and(collect($migas['itemListElement'])->pluck('item')->every(fn ($u) => str_starts_with($u, 'http')))->toBeTrue();
});
