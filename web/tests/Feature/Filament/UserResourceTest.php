<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('administrador');

    $this->actingAs($this->admin);
});

it('lista los usuarios del panel', function () {
    User::factory()->count(2)->create();

    $this->livewire(ListUsers::class)
        ->assertSuccessful();
});

it('crea un usuario con un rol asignado', function () {
    $role = Role::findByName('ventas');

    $this->livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Nueva Editora',
            'email' => 'editora@dante.edu.py',
            'password' => 'password-seguro',
            'roles' => [$role->id],
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::query()->where('email', 'editora@dante.edu.py')->firstOrFail();

    expect($user->hasRole('ventas'))->toBeTrue();
});

it('exige nombre, correo y rol', function () {
    $this->livewire(CreateUser::class)
        ->fillForm([
            'name' => '',
            'email' => '',
            'password' => 'password-seguro',
            'roles' => [],
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'email' => 'required', 'roles' => 'required']);
});

it('un usuario sin permiso no puede ver el listado de usuarios', function () {
    $usuarioVentas = User::factory()->create(['is_active' => true]);
    $usuarioVentas->assignRole('ventas');

    $this->actingAs($usuarioVentas);

    $this->livewire(ListUsers::class)->assertForbidden();
});
