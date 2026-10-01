<?php

use App\Filament\Blocks\PageBlocks;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');

    $this->actingAs($this->admin);
});

it('guarda los bloques vigentes: un encabezado y textos', function () {
    $page = Page::factory()->create();

    $this->livewire(EditPage::class, ['record' => $page->getRouteKey()])
        ->fillForm([
            'blocks' => [
                'bloque-1' => ['type' => 'hero', 'data' => ['title' => ['es' => 'Términos y condiciones'], 'subtitle' => ['es' => 'Cómo usamos el sitio.']]],
                'bloque-2' => ['type' => 'texto', 'data' => ['content' => ['es' => '<p>Primer texto.</p>']]],
                'bloque-3' => ['type' => 'texto', 'data' => ['content' => ['es' => '<p>Segundo texto.</p>']]],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $page->refresh();

    expect($page->blocks)->toHaveCount(3)
        ->and($page->blocks[0]['data']['title']['es'])->toBe('Términos y condiciones')
        ->and($page->blocks[2]['data']['content']['es'])->toBe('<p>Segundo texto.</p>');
});

it('sólo ofrece dos tipos de bloque: encabezado y texto enriquecido', function () {
    $types = collect(PageBlocks::for())->map(fn ($block) => $block->getName());

    expect($types->all())->toBe(['hero', 'texto']);
});

it('el encabezado es único por página', function () {
    expect(PageBlocks::bloque('hero')->getMaxItems())->toBe(1);
});

it('cada texto se nombra por su primer subtítulo o sus primeras palabras', function () {
    $etiqueta = new ReflectionMethod(PageBlocks::class, 'etiquetaDeTexto');

    expect($etiqueta->invoke(null, ['content' => ['es' => '<p>Intro</p><h2>Qué datos recopilamos</h2><p>x</p>']]))->toBe('Qué datos recopilamos')
        ->and($etiqueta->invoke(null, ['content' => ['es' => '<p>Esta política alcanza a todas nuestras actividades de la empresa</p>']]))->toStartWith('Esta política alcanza a todas')
        ->and($etiqueta->invoke(null, ['content' => ['es' => '']]))->toBe('Texto enriquecido')
        ->and($etiqueta->invoke(null, null))->toBe('Texto enriquecido');
});
