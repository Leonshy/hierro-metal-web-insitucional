<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea el administrador inicial. Es seguro volver a correrlo (por ejemplo en cada deploy con `db:seed`):
     * si el usuario ya existe NO se le toca la contraseña ni los datos, sólo se asegura su rol. Para cambiar
     * una contraseña se usa el panel o `php artisan tinker`, nunca el seeder.
     */
    public function run(): void
    {
        $email = env('SITIO_ADMIN_EMAIL', 'admin@hierro-metal.test');
        $password = env('SITIO_ADMIN_PASSWORD', Str::password(16));

        $user = User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrador Hierro Metal',
                'password' => $password,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $user->syncRoles(['administrador']);

        if ($user->wasRecentlyCreated && ! env('SITIO_ADMIN_PASSWORD')) {
            $this->command?->warn("Usuario admin creado: {$user->email} / contraseña generada: {$password} — guardala ahora y cambiala en el primer ingreso.");
        }
    }
}
