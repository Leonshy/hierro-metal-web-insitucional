<?php

use App\Filament\Resources\Horarios\Pages\CreateHorario;
use App\Filament\Resources\Horarios\Pages\ListHorarios;
use App\Models\Horario;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;

function horariosDelCliente(): void
{
    Horario::factory()->create(['etiqueta' => 'Lunes a viernes', 'dias' => [1, 2, 3, 4, 5], 'abre' => '07:00', 'cierra' => '17:00']);
    Horario::factory()->create(['etiqueta' => 'Sábados', 'dias' => [6], 'abre' => '07:00', 'cierra' => '12:00']);
    Horario::factory()->create(['etiqueta' => 'Domingos y feriados', 'dias' => [7], 'abre' => null, 'cierra' => null, 'cerrado' => true]);
}

function enAsuncion(string $fechaHora): Carbon
{
    return Carbon::parse($fechaHora, 'America/Asuncion');
}

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista los horarios', function () {
    horariosDelCliente();

    $this->livewire(ListHorarios::class)->assertSuccessful();
});

it('crea un horario con días y horas', function () {
    $this->livewire(CreateHorario::class)
        ->fillForm(['etiqueta' => 'Sábados', 'dias' => [6], 'abre' => '07:00', 'cierra' => '12:00'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Horario::query()->where('etiqueta', 'Sábados')->firstOrFail()->dias)->toBe([6]);
});

it('exige hora de apertura y cierre salvo que esté cerrado todo el día', function () {
    $this->livewire(CreateHorario::class)
        ->fillForm(['etiqueta' => 'Domingos', 'dias' => [7], 'cerrado' => false])
        ->call('create')
        ->assertHasFormErrors(['abre' => 'required', 'cierra' => 'required']);

    $this->livewire(CreateHorario::class)
        ->fillForm(['etiqueta' => 'Domingos', 'dias' => [7], 'cerrado' => true])
        ->call('create')
        ->assertHasNoFormErrors();
});

it('indica «Abierto ahora» dentro del horario, con la hora de cierre', function () {
    horariosDelCliente();

    // miércoles 7 de octubre de 2026, 10:00 en Asunción
    expect(Horario::estadoAhora(enAsuncion('2026-10-07 10:00')))
        ->toBe(['abierto' => true, 'texto' => 'Abierto ahora · cerramos a las 17:00']);

    // sábado 10, a las 11:59 sigue abierto; a las 12:00 ya no
    expect(Horario::estadoAhora(enAsuncion('2026-10-10 11:59'))['abierto'])->toBeTrue()
        ->and(Horario::estadoAhora(enAsuncion('2026-10-10 12:00'))['abierto'])->toBeFalse();
});

it('dice cuándo vuelve a abrir: hoy, mañana o el próximo día', function () {
    horariosDelCliente();

    expect(Horario::estadoAhora(enAsuncion('2026-10-05 05:00'))['texto'])->toBe('Cerrado ahora · abrimos hoy a las 07:00')   // lunes temprano
        ->and(Horario::estadoAhora(enAsuncion('2026-10-07 18:00'))['texto'])->toBe('Cerrado ahora · abrimos mañana a las 07:00') // miércoles tarde
        ->and(Horario::estadoAhora(enAsuncion('2026-10-09 18:00'))['texto'])->toBe('Cerrado ahora · abrimos mañana a las 07:00') // viernes tarde: abre el sábado
        ->and(Horario::estadoAhora(enAsuncion('2026-10-10 13:00'))['texto'])->toBe('Cerrado ahora · abrimos el lunes a las 07:00') // sábado tarde
        ->and(Horario::estadoAhora(enAsuncion('2026-10-11 10:00'))['texto'])->toBe('Cerrado ahora · abrimos mañana a las 07:00'); // domingo
});

it('convierte la hora a la zona de Asunción antes de decidir', function () {
    horariosDelCliente();

    // 12:00 UTC del miércoles son las 09:00 en Asunción (UTC-3 en octubre de 2026): abierto
    expect(Horario::estadoAhora(Carbon::parse('2026-10-07 12:00', 'UTC'))['abierto'])->toBeTrue();
});

it('sin horarios cargados no inventa información', function () {
    expect(Horario::estadoAhora(enAsuncion('2026-10-07 10:00')))
        ->toBe(['abierto' => false, 'texto' => 'Cerrado ahora']);
});

it('un usuario sin permiso sobre horarios no puede ver el listado', function () {
    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');

    $this->actingAs($ventas);

    $this->livewire(ListHorarios::class)->assertForbidden();
});
