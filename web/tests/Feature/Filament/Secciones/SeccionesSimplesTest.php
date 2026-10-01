<?php

use App\Filament\Pages\Secciones\ContactoPage;
use App\Filament\Pages\Secciones\PreguntasFrecuentesPage;
use App\Filament\Pages\Secciones\ProductosPage;
use App\Filament\Pages\Secciones\UbicacionPage;
use App\Filament\Resources\Familias\FamiliaResource;
use App\Filament\Resources\Familias\Pages\CreateFamilia;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Horarios\HorarioResource;
use App\Filament\Resources\Horarios\Pages\EditHorario;
use App\Filament\Resources\Rubros\Pages\EditRubro;
use App\Filament\Resources\Rubros\RubroResource;
use App\Filament\Secciones\Widgets\FamiliasTabla;
use App\Filament\Secciones\Widgets\FaqsTabla;
use App\Filament\Secciones\Widgets\HorariosTabla;
use App\Filament\Secciones\Widgets\RubrosTabla;
use App\Models\Familia;
use App\Models\Faq;
use App\Models\Horario;
use App\Models\Page;
use App\Models\Rubro;
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

dataset('secciones simples', [
    'Productos' => [ProductosPage::class, 'productos', '/productos', FamiliasTabla::class, Familia::class, true],
    'Preguntas frecuentes' => [PreguntasFrecuentesPage::class, 'preguntas-frecuentes', '/preguntas-frecuentes', FaqsTabla::class, Faq::class, false],
    'Ubicación' => [UbicacionPage::class, 'ubicacion', '/ubicacion', HorariosTabla::class, Horario::class, false],
    'Contacto' => [ContactoPage::class, 'contacto', '/contacto', RubrosTabla::class, Rubro::class, false],
]);

it('abre la sección con el contenido actual de su página', function (string $pagina, string $slug) {
    $titulo = Page::query()->where('slug', $slug)->first()->getTranslation('title', 'es');

    $this->livewire($pagina)->assertSuccessful()->assertFormSet(['titulo' => $titulo]);
})->with('secciones simples');

it('guarda el título, la bajada y el SEO, y se ven en la página pública', function (string $pagina, string $slug, string $url, string $tabla, string $modelo, bool $conBoton) {
    $this->livewire($pagina)
        ->fillForm(['titulo' => 'Título editado desde el panel', 'bajada' => 'Bajada editada desde el panel.', 'seo_titulo' => 'SEO editado', 'seo_descripcion' => 'Descripción SEO editada.'])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->get($url)->assertOk()->assertSee('Título editado desde el panel')->assertSee('Bajada editada desde el panel.')->assertSee('SEO editado', false);
})->with('secciones simples');

it('exige el título', function (string $pagina) {
    $this->livewire($pagina)->fillForm(['titulo' => ''])->call('save')->assertHasFormErrors(['titulo' => 'required']);
})->with('secciones simples');

it('la tabla del módulo aparece al pie de la sección', function (string $pagina, string $slug, string $url, string $tabla, string $modelo) {
    $this->livewire($tabla)->assertSuccessful()->assertCanSeeTableRecords($modelo::all());
})->with('secciones simples');

it('sólo las secciones con botón en la página pública permiten editarlo', function (string $pagina, string $slug, string $url, string $tabla, string $modelo, bool $conBoton) {
    $componente = $this->livewire($pagina);

    $conBoton ? $componente->assertFormFieldExists('boton_texto') : $componente->assertFormFieldDoesNotExist('boton_texto');
})->with('secciones simples');

it('cada módulo de estas secciones ya no tiene entrada propia en el menú', function () {
    foreach ([
        FamiliaResource::class, FaqResource::class,
        HorarioResource::class, RubroResource::class,
    ] as $recurso) {
        expect($recurso::shouldRegisterNavigation())->toBeFalse();
    }
});

it('al crear o editar un módulo se vuelve a su sección', function () {
    $this->livewire(EditFaq::class, ['record' => Faq::query()->first()->getRouteKey()])->fillForm(['activo' => false])->call('save')->assertRedirect(PreguntasFrecuentesPage::getUrl());
    $this->livewire(EditHorario::class, ['record' => Horario::query()->first()->getRouteKey()])->call('save')->assertRedirect(UbicacionPage::getUrl());
    $this->livewire(EditRubro::class, ['record' => Rubro::query()->first()->getRouteKey()])->call('save')->assertRedirect(ContactoPage::getUrl());
    $this->livewire(CreateFamilia::class)->fillForm(['nombre' => 'Familia nueva', 'slug' => 'familia-nueva', 'resumen_home' => 'Un resumen corto de la familia nueva para la tarjeta.', 'bajada' => 'Una bajada de la ficha de la familia nueva, con el detalle necesario.', 'ilustracion' => 'ninguna'])->call('create')->assertRedirect(ProductosPage::getUrl());
});

it('la página pública de Contacto conserva sus textos si se borran los de la sección', function () {
    $this->livewire(ContactoPage::class)->fillForm(['titulo' => 'Contactanos', 'bajada' => ''])->call('save');

    $this->get('/contacto')->assertSee('Contactanos')->assertSee('Mandanos tu lista de materiales');
});
