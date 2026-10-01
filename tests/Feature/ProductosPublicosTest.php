<?php

use App\Models\Familia;
use App\Models\Linea;
use App\Models\Servicio;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

it('lista las cinco familias en /productos', function () {
    $respuesta = $this->get('/productos')->assertOk();

    foreach (Familia::query()->activos()->get() as $familia) {
        $respuesta->assertSee($familia->nombre)->assertSee('/productos/'.$familia->slug, false);
    }
});

it('muestra la ficha de cada familia con su título y su llamado a cotizar', function () {
    foreach (Familia::query()->activos()->get() as $familia) {
        $this->get('/productos/'.$familia->slug)
            ->assertOk()
            ->assertSee($familia->nombre)
            ->assertSee('/contacto?rubro='.$familia->slug, false);
    }
});

it('responde 404 para una familia que no existe o está oculta', function () {
    $this->get('/productos/no-existe')->assertNotFound();

    Familia::query()->where('slug', 'chapas')->update(['activo' => false]);
    $this->get('/productos/chapas')->assertNotFound();
});

it('muestra las tablas de medidas con encabezados accesibles', function () {
    $this->get('/productos/chapas')
        ->assertOk()
        ->assertSee('<caption>Medidas de laminadas en frío</caption>', false)
        ->assertSee('<th scope="col">Espesor</th>', false)
        ->assertSee('<th scope="row">', false)
        ->assertSee('Deslizá para ver todas las columnas');
});

it('en una línea sin medidas pide consultar en lugar de mostrar una tabla vacía', function () {
    $familia = Familia::query()->where('slug', 'chapas')->first();
    Linea::factory()->create([
        'familia_id' => $familia->id,
        'nombre' => 'Línea sin tabla de prueba',
        'medidas' => [],
        'activo' => true,
    ]);

    $this->get('/productos/chapas')
        ->assertOk()
        ->assertSee('Línea sin tabla de prueba')
        ->assertSee('Consultanos las medidas disponibles y el precio.')
        ->assertSee('Pedir medidas y precio');
});

it('no muestra las líneas ocultas', function () {
    $familia = Familia::query()->where('slug', 'chapas')->first();
    Linea::factory()->create(['familia_id' => $familia->id, 'nombre' => 'Línea oculta de prueba', 'activo' => false]);

    $this->get('/productos/chapas')->assertOk()->assertDontSee('Línea oculta de prueba');
});

it('escapa el contenido de las medidas', function () {
    $familia = Familia::query()->where('slug', 'chapas')->first();
    Linea::factory()->create([
        'familia_id' => $familia->id,
        'nombre' => 'Línea XSS',
        'medidas' => [['titulo' => 'T', 'modo' => 'lista', 'columnas' => ['A', 'B'], 'filas_texto' => '<script>alert(1)</script>;x']],
        'activo' => true,
    ]);

    $this->get('/productos/chapas')->assertOk()->assertDontSee('<script>alert(1)</script>', false);
});

it('el botón de WhatsApp lleva el mensaje de la familia', function () {
    $this->get('/productos/chapas')
        ->assertOk()
        ->assertSee('wa.me/', false)
        ->assertSee(rawurlencode('Hola, quiero cotizar chapas'), false);
});

it('muestra la página de servicios con sus servicios, pasos y cierre', function () {
    $this->get('/servicios')
        ->assertOk()
        ->assertSee('Trabajamos el material por vos')
        ->assertSee('Los seis servicios')
        ->assertSee('Cortes a medida')
        ->assertSee('Entrega en obra')
        ->assertSee('De tu plano a la obra')
        ->assertSee('Empezar un pedido')
        ->assertSee('/contacto?rubro=servicio-corte', false)
        ->assertSee(rawurlencode('Hola, quiero consultar por un servicio de taller'), false);
});

it('la cantidad de servicios del título sigue a los servicios visibles', function () {
    Servicio::query()->where('nombre', 'Galvanización')->update(['activo' => false]);

    $this->get('/servicios')->assertOk()->assertSee('Los cinco servicios')->assertDontSee('Los seis servicios');
});

it('la cantidad de consultas no crece al agregar familias (sin N+1 en las tarjetas)', function () {
    $contar = function (string $ruta): int {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $this->get($ruta)->assertOk();
        $total = count(DB::getQueryLog());
        DB::disableQueryLog();

        return $total;
    };

    $this->get('/'); // calienta cachés (ajustes) para comparar en igualdad de condiciones
    $this->get('/productos');
    $antes = ['/' => $contar('/'), '/productos' => $contar('/productos')];

    Familia::factory()->count(4)->create(['activo' => true]);

    expect($contar('/'))->toBe($antes['/'])
        ->and($contar('/productos'))->toBe($antes['/productos']);
});

it('el rótulo de cada tarjeta muestra su posición y la cantidad de líneas visibles', function () {
    Linea::query()->where('familia_id', Familia::where('slug', 'chapas')->value('id'))->first()->update(['activo' => false]);

    $this->get('/productos')->assertOk()->assertSee('01 · 6 líneas')->assertSee('02 · 3 líneas')->assertSee('05 · 1 línea');
});
