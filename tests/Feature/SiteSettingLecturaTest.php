<?php

use App\Models\SiteSetting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;

function consultasDe(callable $accion): int
{
    DB::flushQueryLog();
    DB::enableQueryLog();
    $accion();
    $total = count(DB::getQueryLog());
    DB::disableQueryLog();

    return $total;
}

it('lee muchos ajustes con a lo sumo una consulta a la base y una a la caché', function () {
    foreach (range(1, 30) as $i) {
        SiteSetting::set("clave_{$i}", "valor {$i}");
    }

    $consultas = consultasDe(function () {
        foreach (range(1, 30) as $i) {
            expect(SiteSetting::get("clave_{$i}"))->toBe("valor {$i}");
        }
    });

    expect($consultas)->toBeLessThanOrEqual(2);
});

it('devuelve el valor por defecto cuando el ajuste no existe', function () {
    expect(SiteSetting::get('no_existe'))->toBeNull()
        ->and(SiteSetting::get('no_existe', 'respaldo'))->toBe('respaldo');
});

it('al cambiar un ajuste, la próxima lectura ve el valor nuevo', function () {
    SiteSetting::set('telefono_prueba', 'viejo');
    expect(SiteSetting::get('telefono_prueba'))->toBe('viejo');

    SiteSetting::set('telefono_prueba', 'nuevo');

    expect(SiteSetting::get('telefono_prueba'))->toBe('nuevo');
});

it('al editar el registro directamente (como hace el panel) también se actualiza', function () {
    $ajuste = SiteSetting::set('contact_phone_prueba', 'uno');
    expect(SiteSetting::get('contact_phone_prueba'))->toBe('uno');

    $ajuste->update(['value' => 'dos']);

    expect(SiteSetting::get('contact_phone_prueba'))->toBe('dos');
});

it('al borrar un ajuste deja de existir para las lecturas', function () {
    $ajuste = SiteSetting::set('temporal', 'x');
    expect(SiteSetting::get('temporal'))->toBe('x');

    $ajuste->delete();

    expect(SiteSetting::get('temporal', 'fin'))->toBe('fin');
});

it('conserva el tipo booleano de los ajustes de ese tipo', function () {
    SiteSetting::set('interruptor', '1', 'boolean');
    SiteSetting::set('apagado', '0', 'boolean');

    expect(SiteSetting::get('interruptor'))->toBeTrue()
        ->and(SiteSetting::get('apagado'))->toBeFalse();
});

it('una carga de la portada no hace una consulta por ajuste', function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
    $this->get('/');

    $consultas = consultasDe(fn () => $this->get('/')->assertOk());

    expect($consultas)->toBeLessThan(30);
});
