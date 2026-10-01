<?php

use App\Filament\Pages\CatalogoPdf;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\Catalogo;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake(Catalogo::DISCO);
    $this->seed(PermissionSeeder::class);
});

function usuarioConRol(string $rol): User
{
    $usuario = User::factory()->create(['is_active' => true]);
    $usuario->assignRole($rol);

    return $usuario;
}

function catalogoCargado(string $contenido = '%PDF-1.4 catalogo'): string
{
    Storage::disk(Catalogo::DISCO)->put('catalogo/c.pdf', $contenido);
    SiteSetting::set('catalogo_path', 'catalogo/c.pdf', 'text', 'catalogo');

    return 'catalogo/c.pdf';
}

it('sirve el catálogo cargado en /catalogo.pdf', function () {
    catalogoCargado();

    $this->get('/catalogo.pdf')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertDownload('catalogo-hierro-metal.pdf');
});

it('responde 404 si no hay catálogo cargado', function () {
    $this->get('/catalogo.pdf')->assertNotFound();
});

it('responde 404 si el ajuste apunta a un archivo que ya no existe', function () {
    SiteSetting::set('catalogo_path', 'catalogo/fantasma.pdf', 'text', 'catalogo');

    expect(Catalogo::disponible())->toBeFalse();
    $this->get('/catalogo.pdf')->assertNotFound();
});

it('expone la URL pública sólo cuando hay catálogo', function () {
    expect(Catalogo::url())->toBeNull();

    catalogoCargado();

    expect(Catalogo::url())->toBe(route('catalogo'));
});

it('carga el PDF desde el panel y lo deja disponible', function () {
    $this->actingAs(usuarioConRol('administrador'));

    $this->livewire(CatalogoPdf::class)
        ->fillForm([
            'archivo' => UploadedFile::fake()->create('lista.pdf', 200, 'application/pdf'),
            'texto_boton' => 'Bajar catálogo',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Catalogo::disponible())->toBeTrue()
        ->and(Catalogo::textoBoton())->toBe('Bajar catálogo');
    $this->get('/catalogo.pdf')->assertOk();
});

it('rechaza archivos que no son PDF', function () {
    $this->actingAs(usuarioConRol('administrador'));

    $this->livewire(CatalogoPdf::class)
        ->fillForm([
            'archivo' => UploadedFile::fake()->create('lista.xlsx', 50, 'application/vnd.ms-excel'),
            'texto_boton' => 'Catálogo',
        ])
        ->call('save')
        ->assertHasFormErrors(['archivo']);

    expect(Catalogo::disponible())->toBeFalse();
});

it('al reemplazar el PDF borra el anterior del disco', function () {
    $this->actingAs(usuarioConRol('administrador'));

    // El formulario se abre vacío y el catálogo anterior ya estaba guardado: lo que importa es
    // que al guardar uno nuevo el viejo no quede huérfano en el disco.
    $componente = $this->livewire(CatalogoPdf::class);
    $anterior = catalogoCargado();

    $componente
        ->fillForm([
            'archivo' => UploadedFile::fake()->create('nuevo.pdf', 100, 'application/pdf'),
            'texto_boton' => 'Catálogo',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    Storage::disk(Catalogo::DISCO)->assertMissing($anterior);
    expect(Catalogo::ruta())->not->toBe($anterior)->not->toBeNull();
});

it('al quitar el PDF los botones dejan de mostrarse y el archivo se borra', function () {
    $anterior = catalogoCargado();
    $this->actingAs(usuarioConRol('administrador'));

    $this->livewire(CatalogoPdf::class)
        ->fillForm(['archivo' => null, 'texto_boton' => 'Catálogo'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Catalogo::disponible())->toBeFalse();
    Storage::disk(Catalogo::DISCO)->assertMissing($anterior);
    $this->get('/catalogo.pdf')->assertNotFound();
});

it('exige el texto del botón', function () {
    $this->actingAs(usuarioConRol('administrador'));

    $this->livewire(CatalogoPdf::class)
        ->fillForm(['texto_boton' => ''])
        ->call('save')
        ->assertHasFormErrors(['texto_boton' => 'required']);
});

it('no deja entrar al panel de catálogo a quien no tiene permiso de configuración', function () {
    $this->actingAs(usuarioConRol('ventas'));

    expect(CatalogoPdf::canAccess())->toBeFalse();
});
