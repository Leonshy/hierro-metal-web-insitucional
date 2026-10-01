<?php

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

it('crea el administrador inicial con el rol administrador', function () {
    $this->seed(AdminUserSeeder::class);

    $admin = User::query()->where('email', 'admin@hierro-metal.test')->first();

    expect($admin)->not->toBeNull()->and($admin->hasRole('administrador'))->toBeTrue()->and($admin->is_active)->toBeTrue();
});

it('al volver a correrlo no le cambia la contraseña al administrador existente', function () {
    $this->seed(AdminUserSeeder::class);
    $admin = User::query()->where('email', 'admin@hierro-metal.test')->first();
    $admin->update(['password' => 'Contraseña-del-cliente-123']);
    $hashAntes = $admin->fresh()->password;

    $this->seed(AdminUserSeeder::class);
    $this->seed(AdminUserSeeder::class);

    $despues = $admin->fresh();
    expect($despues->password)->toBe($hashAntes)
        ->and(Hash::check('Contraseña-del-cliente-123', $despues->password))->toBeTrue()
        ->and(User::query()->where('email', 'admin@hierro-metal.test')->count())->toBe(1);
});

it('no vuelve a activar ni a renombrar a un administrador que el cliente ya modificó', function () {
    $this->seed(AdminUserSeeder::class);
    User::query()->where('email', 'admin@hierro-metal.test')->update(['name' => 'Nombre real', 'is_active' => false]);

    $this->seed(AdminUserSeeder::class);

    $admin = User::query()->where('email', 'admin@hierro-metal.test')->first();
    expect($admin->name)->toBe('Nombre real')->and($admin->is_active)->toBeFalse();
});

it('con SITIO_ADMIN_EMAIL crea el administrador con ese correo', function () {
    putenv('SITIO_ADMIN_EMAIL=dueno@hierrometal.test');
    $_ENV['SITIO_ADMIN_EMAIL'] = 'dueno@hierrometal.test';

    try {
        $this->seed(AdminUserSeeder::class);
        expect(User::query()->where('email', 'dueno@hierrometal.test')->exists())->toBeTrue();
    } finally {
        putenv('SITIO_ADMIN_EMAIL');
        unset($_ENV['SITIO_ADMIN_EMAIL']);
    }
});
