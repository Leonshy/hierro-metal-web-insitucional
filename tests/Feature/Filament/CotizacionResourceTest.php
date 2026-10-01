<?php

use App\Filament\Resources\Cotizaciones\CotizacionResource;
use App\Filament\Resources\Cotizaciones\Pages\EditCotizacion;
use App\Filament\Resources\Cotizaciones\Pages\ListCotizaciones;
use App\Models\Cotizacion;
use App\Models\CotizacionAdjunto;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');
    $this->actingAs($this->admin);
});

it('lista las cotizaciones recibidas', function () {
    $cotizaciones = Cotizacion::factory()->count(3)->create();

    $this->livewire(ListCotizaciones::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($cotizaciones);
});

it('esconde el spam de la bandeja salvo que se lo pida con el filtro', function () {
    $real = Cotizacion::factory()->create();
    $spam = Cotizacion::factory()->spam()->create();

    $this->livewire(ListCotizaciones::class)
        ->assertCanSeeTableRecords([$real])
        ->assertCanNotSeeTableRecords([$spam])
        ->filterTable('incluir_spam', true)
        ->assertCanSeeTableRecords([$real, $spam]);
});

it('filtra por estado y marca los avisos que no salieron', function () {
    $nueva = Cotizacion::factory()->avisada()->create(['estado' => 'nueva']);
    $ganada = Cotizacion::factory()->avisada()->create(['estado' => 'ganada']);
    $sinAviso = Cotizacion::factory()->create(['estado' => 'nueva']);

    $this->livewire(ListCotizaciones::class)
        ->filterTable('estado', ['ganada'])
        ->assertCanSeeTableRecords([$ganada])
        ->assertCanNotSeeTableRecords([$nueva, $sinAviso])
        ->resetTableFilters()
        ->filterTable('aviso_pendiente', true)
        ->assertCanSeeTableRecords([$sinAviso])
        ->assertCanNotSeeTableRecords([$nueva, $ganada]);
});

it('busca por nombre o teléfono', function () {
    $marcos = Cotizacion::factory()->create(['nombre' => 'Marcos Prueba']);
    $otra = Cotizacion::factory()->create(['nombre' => 'Otra Persona']);

    $this->livewire(ListCotizaciones::class)
        ->searchTable('Marcos')
        ->assertCanSeeTableRecords([$marcos])
        ->assertCanNotSeeTableRecords([$otra]);
});

it('el menú muestra cuántas cotizaciones nuevas hay', function () {
    expect(CotizacionResource::getNavigationBadge())->toBeNull();

    Cotizacion::factory()->count(2)->create();
    Cotizacion::factory()->spam()->create();
    Cotizacion::factory()->create(['estado' => 'ganada']);

    expect(CotizacionResource::getNavigationBadge())->toBe('2');
});

it('las cotizaciones no se crean desde el panel', function () {
    expect(CotizacionResource::canCreate())->toBeFalse();
});

it('ventas puede cambiar el estado, asignarla y escribir notas', function () {
    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');
    $cotizacion = Cotizacion::factory()->create();
    $this->actingAs($ventas);

    $this->livewire(EditCotizacion::class, ['record' => $cotizacion->getRouteKey()])
        ->fillForm(['estado' => 'en_curso', 'asignado_a' => $ventas->id, 'notas_internas' => 'Llamar mañana temprano.'])
        ->call('save')
        ->assertHasNoFormErrors();

    $cotizacion->refresh();

    expect($cotizacion->estado)->toBe('en_curso')
        ->and($cotizacion->asignado_a)->toBe($ventas->id)
        ->and($cotizacion->notas_internas)->toBe('Llamar mañana temprano.');
});

it('los datos del pedido no se pueden editar desde el panel', function () {
    $cotizacion = Cotizacion::factory()->create(['nombre' => 'Nombre Original', 'mensaje' => 'Pedido original']);

    $this->livewire(EditCotizacion::class, ['record' => $cotizacion->getRouteKey()])
        ->fillForm(['nombre' => 'Cambiado', 'mensaje' => 'Cambiado', 'estado' => 'cotizada'])
        ->call('save');

    $cotizacion->refresh();

    expect($cotizacion->nombre)->toBe('Nombre Original')
        ->and($cotizacion->mensaje)->toBe('Pedido original')
        ->and($cotizacion->estado)->toBe('cotizada');
});

it('ventas no puede eliminar una cotización', function () {
    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');

    expect($ventas->can('delete', Cotizacion::factory()->create()))->toBeFalse()
        ->and($ventas->can('update', Cotizacion::factory()->create()))->toBeTrue();
});

it('el editor de contenido no ve las cotizaciones', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('editor');
    $this->actingAs($editor);

    $this->livewire(ListCotizaciones::class)->assertForbidden();
});

it('la ficha ofrece responder por WhatsApp con el teléfono del pedido', function () {
    $cotizacion = Cotizacion::factory()->create(['nombre' => 'Marcos Prueba', 'telefono' => '0981 000 111']);

    $this->livewire(EditCotizacion::class, ['record' => $cotizacion->getRouteKey()])
        ->assertActionVisible('whatsapp')
        ->assertActionHasUrl('whatsapp', $cotizacion->whatsappUrl());

    expect($cotizacion->whatsappUrl())->toContain('595981000111');
});

it('la ficha lista los adjuntos con su enlace de descarga privado', function () {
    $cotizacion = Cotizacion::factory()->create();
    $adjunto = CotizacionAdjunto::query()->create([
        'cotizacion_id' => $cotizacion->id, 'disco' => 'local', 'ruta' => 'cotizaciones/x/a.pdf',
        'nombre_original' => 'plano-galpon.pdf', 'mime' => 'application/pdf', 'tamano' => 2048,
    ]);

    $this->livewire(EditCotizacion::class, ['record' => $cotizacion->getRouteKey()])
        ->assertSee('plano-galpon.pdf')
        ->assertSeeHtml(route('cotizaciones.adjunto', $adjunto));
});

it('explica en la ficha por qué un aviso no salió', function () {
    $cotizacion = Cotizacion::factory()->create(['mail_intentos' => 2]);

    $this->livewire(EditCotizacion::class, ['record' => $cotizacion->getRouteKey()])
        ->assertSee('Todavía no salió (2 intentos)');
});

it('la auditoría registra el seguimiento pero no copia los datos personales del pedido', function () {
    $cotizacion = Cotizacion::factory()->create(['nombre' => 'Dato Personal', 'telefono' => '0981 555 555']);

    $cotizacion->update(['estado' => 'cotizada', 'notas_internas' => 'Se envió presupuesto.']);

    $actividad = Activity::query()->where('subject_type', Cotizacion::class)->latest('id')->firstOrFail();
    $propiedades = json_encode($actividad->properties);

    expect($propiedades)->toContain('cotizada')
        ->and($propiedades)->not->toContain('Dato Personal')
        ->and($propiedades)->not->toContain('555 555');
});

it('exporta a CSV lo que se ve, neutralizando fórmulas de Excel', function () {
    Cotizacion::factory()->create(['nombre' => '=HYPERLINK("http://malo.test")', 'mensaje' => 'Pedido normal']);

    $this->livewire(ListCotizaciones::class)
        ->callAction('exportar')
        ->assertFileDownloaded('cotizaciones-'.now()->format('Y-m-d').'.csv');

    expect(ListCotizaciones::celdaSegura('=SUM(A1)'))->toBe("'=SUM(A1)")
        ->and(ListCotizaciones::celdaSegura('+54 11'))->toBe("'+54 11")
        ->and(ListCotizaciones::celdaSegura('@usuario'))->toBe("'@usuario")
        ->and(ListCotizaciones::celdaSegura('Texto común'))->toBe('Texto común')
        ->and(ListCotizaciones::celdaSegura(12))->toBe('12');
});
