<?php

use App\Filament\Pages\Secciones\CalidadPage;
use App\Filament\Resources\Compromisos\Pages\EditCompromiso;
use App\Filament\Secciones\Widgets\CompromisosTabla;
use App\Models\Compromiso;
use App\Models\Page;
use App\Models\User;
use App\Support\TextoEnBloques;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    Storage::fake('local');
    $this->seed(DatabaseSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

function textosDeCalidad(): array
{
    return collect(Page::query()->where('slug', 'calidad')->first()->blocks)->where('type', 'texto')->pluck('data.content.es')->values()->all();
}

it('la política de calidad se siembra en textos separados, no en uno solo', function () {
    $textos = textosDeCalidad();

    expect($textos)->toHaveCount(3)
        ->and($textos[0])->toContain('importamos y comercializamos')->not->toContain('<h2')
        ->and($textos[1])->toStartWith('<h2>Qué significa esto para tu obra')->not->toContain('Ámbito de aplicación')
        ->and($textos[2])->toStartWith('<h2>Ámbito de aplicación');
});

it('abre la sección con el encabezado y los tres textos', function () {
    $componente = $this->livewire(CalidadPage::class)->assertSuccessful();

    expect($componente->get('data.textos'))->toHaveCount(3);
});

it('guarda los cambios de un texto sin tocar los otros', function () {
    $antes = textosDeCalidad();
    $componente = $this->livewire(CalidadPage::class);
    $clave = array_keys($componente->get('data.textos'))[0];

    $componente
        ->set("data.textos.{$clave}.data.content.es", '<p>Introducción nueva, escrita por el cliente.</p>')
        ->call('save')
        ->assertHasNoFormErrors();

    $despues = textosDeCalidad();

    expect($despues)->toHaveCount(3)
        ->and($despues[0])->toContain('Introducción nueva')
        ->and($despues[1])->toBe($antes[1])
        ->and($despues[2])->toBe($antes[2]);
});

it('en la página pública el primer texto va antes de los compromisos y los demás después', function () {
    $html = $this->get('/calidad')->assertOk()->getContent();

    $intro = strpos($html, 'importamos y comercializamos');
    $compromisos = strpos($html, 'Nuestros compromisos');
    $queSignifica = strpos($html, 'Qué significa esto para tu obra');
    $ambito = strpos($html, 'Ámbito de aplicación');

    expect($intro)->toBeLessThan($compromisos)
        ->and($compromisos)->toBeLessThan($queSignifica)
        ->and($queSignifica)->toBeLessThan($ambito);
});

it('un texto nuevo agregado desde el panel aparece después de los compromisos', function () {
    $componente = $this->livewire(CalidadPage::class);
    $textos = $componente->get('data.textos');
    $textos['nuevo-1'] = ['type' => 'texto', 'data' => ['content' => ['es' => '<h2>Certificados por partida</h2><p>Pedilos al cotizar.</p>']]];

    $componente->set('data.textos', $textos)->call('save')->assertHasNoFormErrors();

    expect(textosDeCalidad())->toHaveCount(4);

    $html = $this->get('/calidad')->getContent();
    expect(strpos($html, 'Certificados por partida'))->toBeGreaterThan(strpos($html, 'Nuestros compromisos'));
});

it('limpia el HTML peligroso de los textos antes de guardarlo', function () {
    $componente = $this->livewire(CalidadPage::class);
    $clave = array_keys($componente->get('data.textos'))[0];

    $componente->set("data.textos.{$clave}.data.content.es", '<p>Hola</p><script>alert(1)</script><img src=x onerror=alert(2)>')->call('save');

    expect(textosDeCalidad()[0])->toContain('<p>Hola</p>')->not->toContain('<script')->not->toContain('onerror');
});

it('el título y el SEO se guardan en la página', function () {
    $this->livewire(CalidadPage::class)
        ->fillForm(['titulo' => 'Calidad certificada', 'bajada' => 'Respaldo del fabricante.', 'seo_titulo' => 'Calidad · Hierro Metal'])
        ->call('save')->assertHasNoFormErrors();

    $this->get('/calidad')->assertSee('Calidad certificada')->assertSee('Respaldo del fabricante.')->assertSee('Calidad · Hierro Metal', false);
});

it('una página con un solo texto largo (formato anterior) se sigue mostrando partida en el primer subtítulo', function () {
    $pagina = Page::query()->where('slug', 'calidad')->first();
    $pagina->update(['blocks' => [
        collect($pagina->blocks)->firstWhere('type', 'hero'),
        ['type' => 'texto', 'data' => ['content' => ['es' => '<p>Intro vieja.</p><h2>Título de después</h2><p>Cuerpo.</p>']]],
    ]]);

    $html = $this->get('/calidad')->assertOk()->getContent();

    expect(strpos($html, 'Intro vieja.'))->toBeLessThan(strpos($html, 'Nuestros compromisos'))
        ->and(strpos($html, 'Título de después'))->toBeGreaterThan(strpos($html, 'Nuestros compromisos'));
});

it('la migración separa un texto largo existente y es segura de repetir', function () {
    $pagina = Page::query()->where('slug', 'calidad')->first();
    $pagina->update(['blocks' => [
        collect($pagina->blocks)->firstWhere('type', 'hero'),
        ['type' => 'texto', 'data' => ['content' => ['es' => '<p>Intro.</p><h2>Uno</h2><p>a</p><h2>Dos</h2><p>b</p>']]],
    ]]);

    $migracion = require database_path('migrations/2026_10_02_000002_separa_el_texto_de_la_politica_de_calidad.php');
    $migracion->up();
    $migracion->up();

    expect(textosDeCalidad())->toHaveCount(3)->and(textosDeCalidad()[1])->toStartWith('<h2>Uno</h2>');
});

it('TextoEnBloques parte por títulos y deja la introducción primero', function () {
    expect(TextoEnBloques::partir('<p>Intro</p><h2>A</h2><p>a</p><h2>B</h2><p>b</p>'))->toBe(['<p>Intro</p>', '<h2>A</h2><p>a</p>', '<h2>B</h2><p>b</p>'])
        ->and(TextoEnBloques::partir('<h2>Sólo</h2><p>x</p>'))->toBe(['<h2>Sólo</h2><p>x</p>'])
        ->and(TextoEnBloques::partir('   '))->toBe([]);
});

it('los compromisos se ven al pie de la sección y su módulo vuelve a ella', function () {
    $this->livewire(CompromisosTabla::class)->assertSuccessful()->assertCanSeeTableRecords(Compromiso::all());

    $this->livewire(EditCompromiso::class, ['record' => Compromiso::query()->first()->getRouteKey()])
        ->call('save')->assertRedirect(CalidadPage::getUrl());
});
