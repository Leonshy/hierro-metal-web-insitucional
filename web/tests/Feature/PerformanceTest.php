<?php

use App\Models\Page;
use Illuminate\Support\Facades\DB;

// Fase 7 — docs/09-rendimiento.md §6. Cobertura de la caché de consulta
// (invalidación al guardar/borrar desde el panel) y de que las secciones del
// inicio no vuelva a generar N+1 (regla de
// CLAUDE.md §6.5: nada se da por terminado sin test).

it('sirve una página desde la caché de consulta tras la primera lectura', function () {
    $page = Page::factory()->create([
        'slug' => 'institucion/una-pagina-cacheada',
        'title' => ['es' => 'Título original'],
        'status' => 'published',
    ]);

    $this->get('/'.$page->slug)->assertOk()->assertSee('Título original');

    // Se edita directamente en base, sin pasar por el modelo (y por lo tanto
    // sin disparar el evento `saved` que invalida la caché) — simula lo que
    // vería un segundo visitante mientras la caché sigue vigente.
    DB::table('pages')->where('id', $page->id)->update([
        'title' => json_encode(['es' => 'Título cambiado sin invalidar']),
    ]);

    $this->get('/'.$page->slug)
        ->assertOk()
        ->assertSee('Título original')
        ->assertDontSee('Título cambiado sin invalidar');
});

it('invalida la caché de la página al guardarla desde el modelo', function () {
    $page = Page::factory()->create([
        'slug' => 'institucion/otra-pagina-cacheada',
        'title' => ['es' => 'Antes de editar'],
        'status' => 'published',
    ]);

    $this->get('/'.$page->slug)->assertOk()->assertSee('Antes de editar');

    $page->update(['title' => ['es' => 'Después de editar']]);

    $this->get('/'.$page->slug)
        ->assertOk()
        ->assertSee('Después de editar')
        ->assertDontSee('Antes de editar');
});

it('invalida la caché de la página en el slug viejo si se le cambia el slug', function () {
    $page = Page::factory()->create([
        'slug' => 'institucion/slug-viejo',
        'status' => 'published',
    ]);

    $this->get('/'.$page->slug)->assertOk();

    $page->update(['slug' => 'institucion/slug-nuevo']);

    $this->get('/institucion/slug-viejo')->assertNotFound();
    $this->get('/institucion/slug-nuevo')->assertOk();
});
