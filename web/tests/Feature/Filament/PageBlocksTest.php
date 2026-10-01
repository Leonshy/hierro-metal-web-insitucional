<?php

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

it('guarda los bloques del catálogo vigentes (preguntas frecuentes y mapa)', function () {
    $page = Page::factory()->create();

    $this->livewire(EditPage::class, ['record' => $page->getRouteKey()])
        ->fillForm([
            'blocks' => [
                'bloque-1' => [
                    'type' => 'faq',
                    'data' => [
                        'items' => [
                            ['question' => ['es' => '¿Cortan a medida?'], 'answer' => ['es' => 'Sí, según tu plano.']],
                        ],
                    ],
                ],
                'bloque-2' => [
                    'type' => 'mapa',
                    'data' => [
                        'address' => 'Pedro Getto esq. Cadete Sisa, Fernando de la Mora',
                        'latitude' => -25.3,
                        'longitude' => -57.55,
                    ],
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $page->refresh();

    expect($page->blocks)->toHaveCount(2);
    expect($page->blocks[0]['data']['items'][0]['question']['es'])->toBe('¿Cortan a medida?');
    expect($page->blocks[1]['data']['address'])->toBe('Pedro Getto esq. Cadete Sisa, Fernando de la Mora');
});

it('ya no ofrece los bloques que eran del colegio', function () {
    $types = collect(\App\Filament\Blocks\PageBlocks::for())->map(fn ($block) => $block->getName());

    expect($types->all())->not->toContain('galeria', 'testimonios', 'formulario', 'listado_comunicados', 'documentos', 'selector_sede', 'listado_noticias');
});
