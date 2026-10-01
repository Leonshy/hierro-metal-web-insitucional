<?php

use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');
    $this->actingAs($admin);
});

it('lista faqs', function () {
    Faq::factory()->count(3)->create();

    $this->livewire(ListFaqs::class)->assertSuccessful();
});

it('crea un registro de faqs y lo deja al final del orden', function () {
    Faq::factory()->create(['orden' => 4]);

    $this->livewire(CreateFaq::class)
        ->fillForm([
            'pregunta' => '¿Cortan el material a medida?',
            'respuesta' => '<p>Sí. Cortamos, plegamos y perforamos según tu plano.</p>',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $nuevo = Faq::query()->where('pregunta', '¿Cortan el material a medida?')->firstOrFail();

    expect($nuevo->orden)->toBe(5);
});

it('edita un registro de faqs', function () {
    $registro = Faq::factory()->create();

    $this->livewire(EditFaq::class, ['record' => $registro->getRouteKey()])
        ->fillForm(['activo' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($registro->refresh()->activo)->toBeFalse();
});

it('guarda una auditoría cuando cambia un registro de faqs', function () {
    $registro = Faq::factory()->create();

    $registro->update(['activo' => false]);

    expect(Activity::query()->where('subject_type', Faq::class)->exists())->toBeTrue();
});

it('un usuario sin permiso sobre faqs no puede ver el listado', function () {
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('ventas');

    $this->actingAs($editor);

    $this->livewire(ListFaqs::class)->assertForbidden();
});
