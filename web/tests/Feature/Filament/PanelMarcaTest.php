<?php

use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Database\Seeders\ConfiguracionSeeder;
use Database\Seeders\PermissionSeeder;

/** Dirección del panel según la configuración (en pruebas no tiene por qué ser /panel). */
function panelUrl(string $ruta = ''): string
{
    return '/'.trim(config('sitio.admin_path'), '/').$ruta;
}

it('la pantalla de acceso muestra el logo de Hierro Metal', function () {
    $this->get(panelUrl('/login'))
        ->assertOk()
        ->assertSee('images/logo-hierro-metal.svg', false)
        ->assertSee('Panel Hierro Metal'); // nombre del panel, como texto alternativo del logo
});

it('el logo del panel existe como archivo y es un SVG', function () {
    expect(file_get_contents(public_path('images/logo-hierro-metal.svg')))->toContain('<svg');
});

it('con la sesión iniciada, la barra del panel también lleva el logo', function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');

    $this->actingAs($admin)->get(panelUrl())->assertOk()->assertSee('images/logo-hierro-metal.svg', false);
});

it('los títulos de Usuarios, Archivos y Configuraciones están en español', function () {
    expect(UserResource::getModelLabel())->toBe('usuario')
        ->and(UserResource::getPluralModelLabel())->toBe('usuarios')
        ->and(MediaResource::getPluralModelLabel())->toBe('archivos')
        ->and(SiteSettingResource::getPluralModelLabel())->toBe('configuraciones');
});

it('en los formularios del panel no hay selectores de idioma (el sitio es sólo en español)', function () {
    foreach ([
        app_path('Filament/Resources/Pages/Schemas/PageForm.php'),
        app_path('Filament/Blocks/PageBlocks.php'),
    ] as $archivo) {
        $codigo = file_get_contents($archivo);

        expect($codigo)->not->toContain("Tab::make('Español')")->not->toContain("Tab::make('Italiano')")->not->toContain('italianEnabled');
    }
});

it('el formulario de Menús y la lista de configuraciones no tienen restos de idioma', function () {
    expect(file_get_contents(app_path('Livewire/ManageMenuItems.php')))
        ->not->toContain("Tab::make('Español')")->not->toContain("Tab::make('Italiano')")->not->toContain('italianEnabled');

    $tabla = file_get_contents(app_path('Filament/Resources/SiteSettings/Tables/SiteSettingsTable.php'));
    expect($tabla)->not->toContain("'idioma' => 'Idioma'");
});

it('el filtro de secciones de Configuraciones sale de los ajustes que existen', function () {
    ConfiguracionSeeder::class;
    $this->seed(PermissionSeeder::class);
    $this->seed(ConfiguracionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');

    $this->actingAs($admin)->get('/'.trim(config('sitio.admin_path'), '/').'/site-settings')
        ->assertOk()->assertSee('Redes sociales')->assertSee('Catálogo')->assertDontSee('Idioma');
});
