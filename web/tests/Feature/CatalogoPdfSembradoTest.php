<?php

use App\Models\SiteSetting;
use App\Support\Catalogo;
use Database\Seeders\CatalogoPdfSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    Storage::fake(Catalogo::DISCO);
});

it('deja cargado el catálogo 2026 al sembrar el sitio', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Catalogo::disponible())->toBeTrue();
    Storage::disk(Catalogo::DISCO)->assertExists(Catalogo::ruta());
    $this->get('/catalogo.pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf');
});

it('el PDF sembrado es un PDF de verdad', function () {
    $this->seed(CatalogoPdfSeeder::class);

    expect(Storage::disk(Catalogo::DISCO)->get(Catalogo::ruta()))->toStartWith('%PDF-');
});

it('no pisa el catálogo que el cliente subió desde el panel', function () {
    Storage::disk(Catalogo::DISCO)->put('catalogo/propio.pdf', '%PDF-1.4 del cliente');
    SiteSetting::set('catalogo_path', 'catalogo/propio.pdf', 'text', 'catalogo');

    $this->seed(CatalogoPdfSeeder::class);

    expect(Catalogo::ruta())->toBe('catalogo/propio.pdf')
        ->and(Storage::disk(Catalogo::DISCO)->get('catalogo/propio.pdf'))->toBe('%PDF-1.4 del cliente');
});

it('es seguro volver a correrlo: no duplica ni cambia nada', function () {
    $this->seed(CatalogoPdfSeeder::class);
    $primera = Catalogo::ruta();

    $this->seed(CatalogoPdfSeeder::class);

    expect(Catalogo::ruta())->toBe($primera)
        ->and(count(Storage::disk(Catalogo::DISCO)->files('catalogo')))->toBe(1);
});

it('con el catálogo cargado, /productos muestra el botón de descarga arriba y en la tarjeta destacada', function () {
    $this->seed(DatabaseSeeder::class);

    $html = $this->get('/productos')->assertOk()->getContent();

    expect(substr_count($html, 'href="'.route('catalogo').'"'))->toBeGreaterThanOrEqual(2)
        ->and($html)->toContain('↓ Descargar catálogo')
        ->and($html)->toContain('↓ Descargar PDF');
});

it('sin catálogo cargado no se muestra ningún botón de descarga roto', function () {
    $this->seed(DatabaseSeeder::class);
    SiteSetting::set('catalogo_path', '', 'text', 'catalogo');

    $this->get('/productos')->assertOk()->assertDontSee('Descargar catálogo')->assertDontSee('Descargar PDF');
});
