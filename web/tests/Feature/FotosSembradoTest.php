<?php

use App\Models\Familia;
use App\Models\Media;
use App\Models\Page;
use App\Support\Encabezado;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\FotosSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

it('deja una foto del catálogo en cada familia, con texto alternativo', function () {
    $familias = Familia::query()->with('media')->get();

    expect($familias)->toHaveCount(5);

    foreach ($familias as $familia) {
        expect($familia->media)->not->toBeNull()
            ->and($familia->media->alt)->not->toBeEmpty()
            ->and($familia->media->conversions)->toBeArray();
        Storage::disk('media')->assertExists($familia->media->path);
    }
});

it('pone la foto de portada en el hero de la página inicio', function () {
    $imagen = Encabezado::de('inicio')->imagen;

    expect($imagen)->toBe('bloques/portada.jpg');
    Storage::disk('public')->assertExists($imagen);
});

it('no pisa las fotos que el cliente ya cambió desde el panel', function () {
    $propia = Media::factory()->create();
    Familia::query()->where('slug', 'chapas')->update(['media_id' => $propia->id]);
    $pagina = Page::query()->where('slug', 'inicio')->first();
    $bloques = $pagina->blocks;
    $bloques[0]['data']['image'] = 'bloques/otra.jpg';
    $pagina->update(['blocks' => $bloques]);
    $totalMedios = Media::count();

    $this->seed(FotosSeeder::class);

    expect(Familia::query()->where('slug', 'chapas')->value('media_id'))->toBe($propia->id)
        ->and(Encabezado::de('inicio')->imagen)->toBe('bloques/otra.jpg')
        ->and(Media::count())->toBe($totalMedios);
});

it('muestra las fotos en la portada', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('storage/bloques/portada.jpg', false)
        ->assertSee('class="ficha-foto"', false);
});
