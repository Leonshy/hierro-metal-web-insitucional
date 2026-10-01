<?php

use App\Filament\Resources\Familias\Pages\CreateFamilia;
use App\Filament\Resources\Familias\Pages\EditFamilia;
use App\Filament\Resources\Familias\Pages\ListFamilias;
use App\Filament\Resources\Familias\RelationManagers\LineasRelationManager;
use App\Models\Familia;
use App\Models\Linea;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Filament\Actions\Testing\TestAction;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista las familias', function () {
    Familia::factory()->count(3)->create();

    $this->livewire(ListFamilias::class)->assertSuccessful();
});

it('crea una familia y la deja al final del orden', function () {
    Familia::factory()->create(['orden' => 2]);

    $this->livewire(CreateFamilia::class)
        ->fillForm([
            'nombre' => 'Chapas de acero',
            'slug' => 'chapas',
            'resumen_home' => 'Laminadas en frío y caliente, galvanizadas e inoxidables.',
            'bajada' => 'Para cubiertas, cerramientos y trabajos de herrería.',
            'ilustracion' => 'chapas',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Familia::query()->where('slug', 'chapas')->firstOrFail()->orden)->toBe(3);
});

it('no admite una dirección web repetida ni con formato inválido', function () {
    Familia::factory()->create(['slug' => 'chapas']);

    $this->livewire(CreateFamilia::class)
        ->fillForm(['nombre' => 'Otra', 'slug' => 'chapas', 'resumen_home' => 'x', 'bajada' => 'x', 'ilustracion' => 'chapas'])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique']);

    $this->livewire(CreateFamilia::class)
        ->fillForm(['nombre' => 'Otra', 'slug' => 'Con Espacios', 'resumen_home' => 'x', 'bajada' => 'x', 'ilustracion' => 'chapas'])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'regex']);
});

it('calcula el rótulo «01 · 2 líneas» a partir del orden y de las líneas visibles', function () {
    $primera = Familia::factory()->create();
    $segunda = Familia::factory()->create();
    Linea::factory()->count(2)->create(['familia_id' => $primera->id]);
    Linea::factory()->create(['familia_id' => $primera->id, 'activo' => false]);
    Linea::factory()->create(['familia_id' => $segunda->id]);

    expect($primera->rotulo())->toBe('01 · 2 líneas')
        ->and($segunda->rotulo())->toBe('02 · 1 línea');
});

it('arma el mensaje de WhatsApp: el propio o uno por defecto', function () {
    expect(Familia::factory()->make(['nombre' => 'Chapas de acero', 'mensaje_whatsapp' => null])->mensajeWhatsapp())
        ->toBe('Hola, quiero cotizar chapas de acero.')
        ->and(Familia::factory()->make(['mensaje_whatsapp' => 'Hola, necesito chapas'])->mensajeWhatsapp())
        ->toBe('Hola, necesito chapas');
});

it('un usuario sin permiso sobre productos no puede ver las familias', function () {
    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');

    $this->actingAs($ventas);

    $this->livewire(ListFamilias::class)->assertForbidden();
});

it('el editor sí puede ver y editar las familias', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('editor');
    $this->actingAs($editor);

    $this->livewire(ListFamilias::class)->assertSuccessful();
});

it('crea una línea con una tabla de medidas pegada desde Excel', function () {
    $familia = Familia::factory()->create();

    $this->livewire(LineasRelationManager::class, ['ownerRecord' => $familia, 'pageClass' => EditFamilia::class])
        ->callAction(TestAction::make('create')->table(), [
            'nombre' => 'Laminadas en frío',
            'descripcion' => 'Chapa negra en distintos espesores.',
            'usos' => ['Estructuras', 'Herrería'],
            'medidas' => [[
                'titulo' => 'Laminadas en frío',
                'modo' => 'tabla',
                'columnas' => ['Espesor', 'Largo (mm)', 'Ancho (mm)'],
                'filas_texto' => "N°16 – 1,50\t2000/2400/3000\t1000/1200/1500\nN°18 – 1,2\t2000/2400/3000\t1000/1200/1500",
            ]],
            'activo' => true,
        ])
        ->assertHasNoFormErrors();

    $linea = $familia->lineas()->firstOrFail();

    expect($linea->tablas())->toHaveCount(1)
        ->and($linea->tablas()[0]['filas'])->toBe([
            ['N°16 – 1,50', '2000/2400/3000', '1000/1200/1500'],
            ['N°18 – 1,2', '2000/2400/3000', '1000/1200/1500'],
        ])
        ->and($linea->tablas()[0]['columnas'])->toBe(['Espesor', 'Largo (mm)', 'Ancho (mm)']);
});

it('rechaza una fila con otra cantidad de celdas que de columnas', function () {
    $familia = Familia::factory()->create();

    $this->livewire(LineasRelationManager::class, ['ownerRecord' => $familia, 'pageClass' => EditFamilia::class])
        ->callAction(TestAction::make('create')->table(), [
            'nombre' => 'Galvanizadas',
            'descripcion' => 'Con recubrimiento de zinc.',
            'medidas' => [[
                'modo' => 'tabla',
                'columnas' => ['Espesor', 'Largo (mm)'],
                'filas_texto' => "N°30\t2000\nN°28\t2000\t1000",
            ]],
        ])
        ->assertHasFormErrors();

    expect($familia->lineas()->count())->toBe(0);
});

it('admite el formato «lista» con punto y coma, para textos largos', function () {
    $fila = Linea::parseFilas("1000 × 2000 · 1200 × 2400; N°30 · 28 · 27\n\n");

    expect($fila)->toBe([['1000 × 2000 · 1200 × 2400', 'N°30 · 28 · 27']]);
});

it('una línea sin tablas se muestra sin medidas', function () {
    $linea = Linea::factory()->create(['medidas' => null]);

    expect($linea->tieneMedidas())->toBeFalse()
        ->and($linea->tablas())->toBe([]);
});

it('una línea con medidas las expone listas para mostrar', function () {
    $linea = Linea::factory()->conMedidas()->create();

    expect($linea->tieneMedidas())->toBeTrue()
        ->and($linea->tablas()[0]['titulo'])->toBe('Laminadas en frío');
});

it('las líneas de una familia se devuelven en el orden cargado', function () {
    $familia = Familia::factory()->create();
    $b = Linea::factory()->create(['familia_id' => $familia->id, 'nombre' => 'B']);
    $a = Linea::factory()->create(['familia_id' => $familia->id, 'nombre' => 'A']);

    expect($familia->lineas()->pluck('nombre')->all())->toBe(['B', 'A'])
        ->and($b->orden)->toBeLessThan($a->orden);
});

it('borrar una familia borra sus líneas', function () {
    $familia = Familia::factory()->create();
    Linea::factory()->count(2)->create(['familia_id' => $familia->id]);

    $familia->delete();

    expect(Linea::query()->count())->toBe(0);
});
