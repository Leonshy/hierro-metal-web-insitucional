<?php

use App\Models\Novedad;
use App\Models\Page;
use App\Models\SeccionInicio;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

/** Novedades (blog): si no hay ninguna visible, la sección entera desaparece; si hay, aparece en el menú, la portada y el mapa. */
beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

function enlacesANovedades(string $html): int
{
    return preg_match_all('#href="[^"]*/novedades"#', $html);
}

it('sin novedades no hay página, ni menú, ni bloque en la portada, ni mapa del sitio', function () {
    $this->get('/novedades')->assertNotFound();

    $inicio = $this->get('/')->assertOk();
    expect(enlacesANovedades($inicio->getContent()))->toBe(0);
    $inicio->assertDontSee('Lo último en Hierro Metal');

    $this->get('/sitemap.xml')->assertDontSee('/novedades', false);
});

it('una novedad oculta, programada o sin fecha tampoco hace aparecer la sección', function () {
    Novedad::factory()->create(['activo' => false]);
    Novedad::factory()->programada()->create();
    Novedad::factory()->create(['publicada_en' => null]);

    $this->get('/novedades')->assertNotFound();
    expect(enlacesANovedades($this->get('/')->getContent()))->toBe(0);
});

it('con una novedad publicada aparece en el menú, el pie, la portada y el mapa del sitio', function () {
    $novedad = Novedad::factory()->create(['titulo' => 'Llegó un contenedor de chapas']);

    $inicio = $this->get('/')->assertOk()->assertSee('Lo último en Hierro Metal')->assertSee('Llegó un contenedor de chapas');
    expect(enlacesANovedades($inicio->getContent()))->toBeGreaterThanOrEqual(3);

    $this->get('/novedades')->assertOk()->assertSee('Llegó un contenedor de chapas');
    $this->get('/sitemap.xml')->assertSee('/novedades', false)->assertSee('/novedades/'.$novedad->slug, false);
});

it('la portada muestra sólo las 3 últimas', function () {
    foreach (range(1, 5) as $i) {
        Novedad::factory()->create(['titulo' => "Nota número $i", 'publicada_en' => now()->subDays(10 - $i)]);
    }

    $this->get('/')->assertSee('Nota número 5')->assertSee('Nota número 4')->assertSee('Nota número 3')
        ->assertDontSee('Nota número 2')->assertDontSee('Nota número 1');
});

it('el listado va de a 9 por página, de la más nueva a la más vieja', function () {
    foreach (range(1, 10) as $i) {
        Novedad::factory()->create(['titulo' => "Nota número $i", 'publicada_en' => now()->subDays(20 - $i)]);
    }

    $this->get('/novedades')->assertSee('Nota número 10')->assertDontSee('Nota número 1<', false)->assertSee('Página 1 de 2');
    $this->get('/novedades?page=2')->assertSee('Nota número 1<', false);
});

it('abre la nota con su contenido, su fecha y los datos estructurados', function () {
    $novedad = Novedad::factory()->create(['contenido' => '<h2>Stock renovado</h2><p>Ya podés pedir tus medidas.</p>']);

    $this->get(route('novedades.show', $novedad->slug))
        ->assertOk()
        ->assertSee('Stock renovado')
        ->assertSee('"@type":"Article"', false)
        ->assertSee('property="og:type" content="article"', false);
});

it('una nota que no existe da 404', function () {
    Novedad::factory()->create();

    $this->get('/novedades/no-existe')->assertNotFound();
});

it('limpia el HTML peligroso del contenido al guardar', function () {
    $novedad = Novedad::factory()->create(['contenido' => '<p>Hola</p><script>alert(1)</script><img src="x" onerror="alert(2)">']);

    expect($novedad->contenido)->not->toContain('<script')->not->toContain('onerror')->toContain('Hola');
});

it('con la sección en borrador no se ve aunque haya novedades', function () {
    $novedad = Novedad::factory()->create();
    Page::query()->where('slug', 'novedades')->update(['status' => 'draft']);

    $this->get('/novedades')->assertNotFound();
    $this->get(route('novedades.show', $novedad->slug))->assertNotFound();
    expect(enlacesANovedades($this->get('/')->getContent()))->toBe(0);
});

it('la portada deja de mostrar el bloque si se desactiva en Inicio', function () {
    Novedad::factory()->create();
    SeccionInicio::query()->where('clave', 'novedades')->update(['activo' => false]);

    $this->get('/')->assertDontSee('Lo último en Hierro Metal');
});

it('una novedad sin publicar sólo la ve, en vista previa, quien tiene sesión del panel', function () {
    $oculta = Novedad::factory()->create(['activo' => false, 'titulo' => 'Borrador secreto']);

    $this->get(route('novedades.show', $oculta->slug))->assertNotFound();

    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');

    $this->actingAs($admin)->get(route('novedades.show', $oculta->slug))->assertOk()->assertSee('Borrador secreto')->assertSee('Vista previa');
});

it('sigue publicada la sección al borrar la última novedad: desaparece sola', function () {
    $novedad = Novedad::factory()->create();
    $this->get('/novedades')->assertOk();

    $novedad->delete();

    $this->get('/novedades')->assertNotFound();
});
