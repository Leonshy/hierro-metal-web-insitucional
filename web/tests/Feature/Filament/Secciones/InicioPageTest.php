<?php

use App\Filament\Pages\Secciones\InicioPage;
use App\Filament\Resources\Diferenciales\Pages\EditDiferencial;
use App\Filament\Secciones\Widgets\DiferencialesTabla;
use App\Filament\Secciones\Widgets\SeccionesInicioTabla;
use App\Models\Diferencial;
use App\Models\Media;
use App\Models\Page;
use App\Models\SeccionInicio;
use App\Models\User;
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

function rotulosDeLaPortada(): array
{
    preg_match_all('#class="rotulo">(\d\d) — ([^<]+)#', test()->get('/')->getContent(), $m, PREG_SET_ORDER);

    return array_map(fn ($x) => [$x[1], trim($x[2])], $m);
}

it('abre la sección Inicio con el hero actual', function () {
    $this->livewire(InicioPage::class)
        ->assertSuccessful()
        ->assertFormSet(['titulo' => 'Materiales metálicos para construir Paraguay', 'boton_texto' => 'Pedir cotización'])
        ->assertFormFieldExists('media_id')
        ->assertFormFieldExists('insignia')
        ->assertFormFieldExists('nota');
});

it('no ofrece decidir la indexación de la portada: siempre se indexa', function () {
    $this->livewire(InicioPage::class)->assertFormFieldDoesNotExist('indexable');
});

it('guarda el hero (títulos, botón, insignia, nota y foto) y la portada lo muestra', function () {
    $foto = Media::factory()->create();

    $this->livewire(InicioPage::class)
        ->fillForm([
            'titulo' => 'Acero para tu obra', 'bajada' => 'Todo en un solo lugar.', 'boton_texto' => 'Cotizá ya', 'boton_url' => '/contacto',
            'insignia' => 'Venta mayorista', 'nota' => 'Con certificado del fabricante', 'media_id' => $foto->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->get('/')->assertOk()->assertSee('Acero para tu obra')->assertSee('Todo en un solo lugar.')->assertSee('Cotizá ya')->assertSee('Venta mayorista')->assertSee('Con certificado del fabricante');
    expect(Page::query()->where('slug', 'inicio')->first()->blocks[0]['data']['media_id'])->toBe($foto->id);
});

it('la insignia y la nota vuelven a sus textos de siempre si se vacían', function () {
    $this->livewire(InicioPage::class)->fillForm(['insignia' => '', 'nota' => ''])->call('save');

    $this->get('/')->assertSee('Importación y venta · Mayorista y minorista')->assertSee('Todas las chapas con certificado de calidad del fabricante');
});

it('cuida que guardar el hero no pierda los demás datos del bloque', function () {
    $antes = Page::query()->where('slug', 'inicio')->first()->blocks[0]['data'];

    $this->livewire(InicioPage::class)->fillForm(['titulo' => 'Otro título'])->call('save');

    $despues = Page::query()->where('slug', 'inicio')->first()->blocks[0]['data'];
    expect($despues['media_id'])->toBe($antes['media_id'])->and($despues['cta_url'])->toBe($antes['cta_url']);
});

it('las cuatro secciones de la portada se muestran numeradas, en su orden inicial', function () {
    expect(rotulosDeLaPortada())->toBe([['01', 'Productos'], ['02', 'Servicios industriales'], ['03', 'Política de calidad'], ['04', 'Contacto']]);
});

it('al desactivar una sección desaparece de la portada y la numeración se ajusta sola', function () {
    SeccionInicio::query()->where('clave', 'servicios')->update(['activo' => false]);

    expect(rotulosDeLaPortada())->toBe([['01', 'Productos'], ['02', 'Política de calidad'], ['03', 'Contacto']]);
});

it('al cambiar el orden la portada sigue ese orden', function () {
    SeccionInicio::query()->where('clave', 'contacto')->update(['orden' => 0]);
    SeccionInicio::query()->where('clave', 'productos')->update(['orden' => 9]);

    expect(array_column(rotulosDeLaPortada(), 1))->toBe(['Contacto', 'Servicios industriales', 'Política de calidad', 'Productos']);
});

it('con todas las secciones desactivadas la portada sigue funcionando', function () {
    SeccionInicio::query()->update(['activo' => false]);

    $this->get('/')->assertOk()->assertSee('Materiales metálicos para construir Paraguay')->assertSee('Stock permanente');
    expect(rotulosDeLaPortada())->toBe([]);
});

it('la tabla de secciones se ve al pie, con su interruptor', function () {
    $this->livewire(SeccionesInicioTabla::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(SeccionInicio::all())
        ->assertTableColumnExists('activo');
});

it('el interruptor de la tabla activa y desactiva la sección', function () {
    $seccion = SeccionInicio::query()->where('clave', 'calidad')->first();

    $this->livewire(SeccionesInicioTabla::class)->call('updateTableColumnState', 'activo', $seccion->getKey(), false);

    expect($seccion->fresh()->activo)->toBeFalse();
});

it('los diferenciales se editan desde Inicio y se vuelve a la sección', function () {
    $this->livewire(DiferencialesTabla::class)->assertSuccessful()->assertCanSeeTableRecords(Diferencial::all());

    $this->livewire(EditDiferencial::class, ['record' => Diferencial::query()->first()->getRouteKey()])->call('save')->assertRedirect(InicioPage::getUrl());
});

it('las cuatro claves de la portada tienen su parte', function () {
    foreach (SeccionInicio::CLAVES as $clave) {
        expect(view()->exists("home.secciones.{$clave}"))->toBeTrue();
    }
});

it('Inicio es la primera entrada del menú de contenido', function () {
    expect(InicioPage::getNavigationSort())->toBe(1);
});
