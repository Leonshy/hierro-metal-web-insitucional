<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

dataset('paginas', ['/', '/productos', '/productos/chapas', '/servicios', '/calidad', '/preguntas-frecuentes', '/ubicacion', '/contacto']);

it('no pide fuentes ni scripts a terceros al cargar la página (todo propio hasta que haya consentimiento)', function (string $ruta) {
    $html = $this->get($ruta)->assertOk()->getContent();

    expect($html)->not->toMatch('#<link[^>]+href="https?://(?!localhost)[^"]*(fonts\.googleapis|fonts\.gstatic|bunny|cdn)#i')
        ->and($html)->not->toMatch('#<script[^>]+src="https?://(?!localhost|127\.0\.0\.1)#i')
        ->and($html)->not->toContain('googletagmanager.com/gtm.js')
        ->and($html)->not->toContain('connect.facebook.net');
})->with('paginas');

it('precarga sólo las fuentes que se ven arriba del pliegue', function () {
    preg_match_all('#<link rel="preload" as="font"[^>]+href="([^"]+)"#', $this->get('/')->getContent(), $m);

    expect($m[1])->toHaveCount(3)->and(implode(' ', $m[1]))->toContain('barlow-condensed-700')->toContain('ibm-plex-sans-400');
});

it('las fuentes usan font-display: swap para no ocultar el texto mientras cargan', function () {
    $html = $this->get('/')->getContent();

    expect(substr_count($html, 'font-display: swap'))->toBeGreaterThanOrEqual(6)
        ->and($html)->not->toContain('font-display: block');
});

it('la foto de portada se pide con prioridad alta, sin carga diferida y con srcset', function () {
    preg_match('#<img[^>]*foto-portada[^>]*>#', $this->get('/')->getContent(), $m);

    expect($m[0])->toContain('fetchpriority="high"')->toContain('srcset=')->not->toContain('loading="lazy"');
});

it('las demás fotos se cargan de forma diferida', function () {
    $html = $this->get('/')->getContent();

    expect(substr_count($html, 'class="ficha-foto"'))->toBeGreaterThanOrEqual(5)
        ->and(substr_count($html, 'loading="lazy"'))->toBeGreaterThanOrEqual(5);
});

it('el .htaccess comprime el texto y cachea los archivos estáticos', function () {
    $htaccess = file_get_contents(public_path('.htaccess'));

    expect($htaccess)->toContain('mod_deflate')->toContain('text/css')->toContain('application/javascript')
        ->and($htaccess)->toContain('max-age=31536000, immutable')
        ->and($htaccess)->toContain('^build/')
        ->and($htaccess)->toContain('index.php [L]'); // sigue conservando el front controller de Laravel
});

it('el enlace del aviso de cookies dice a dónde lleva', function () {
    $this->get('/')->assertSee('Más información en la política de privacidad');
});
