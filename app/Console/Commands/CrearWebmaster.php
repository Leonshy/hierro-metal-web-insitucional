<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

use function Laravel\Prompts\password;

/**
 * Crea la cuenta de mantenimiento de WebParaguay en el entorno donde se corre (local, staging, producción).
 * La contraseña NO está en el repositorio: se pide por pantalla sin mostrarla, o con --generar se crea una
 * aleatoria y se muestra una sola vez. Es seguro volver a correrlo: si la cuenta existe no toca nada.
 */
class CrearWebmaster extends Command
{
    protected $signature = 'usuarios:crear-webmaster {--generar : Genera una contraseña aleatoria y la muestra una sola vez}';

    protected $description = 'Crea la cuenta protegida de mantenimiento (webmaster@webparaguay.com)';

    public function handle(): int
    {
        $existente = User::withTrashed()->where('email', User::EMAIL_WEBMASTER)->first();

        if ($existente) {
            $this->info('La cuenta '.User::EMAIL_WEBMASTER.' ya existe: no se cambió nada.');

            return self::SUCCESS;
        }

        if (! Role::query()->where('name', 'administrador')->exists()) {
            $this->error('Falta el rol «administrador». Corré antes: php artisan db:seed --class=PermissionSeeder');

            return self::FAILURE;
        }

        $contrasena = $this->option('generar')
            ? Str::password(20)
            : password('Contraseña de la cuenta de mantenimiento (no se muestra)', required: true, validate: fn (string $v) => mb_strlen($v) < 12 ? 'Usá al menos 12 caracteres.' : null);

        $usuario = User::query()->create([
            'name' => 'WebParaguay (mantenimiento)',
            'email' => User::EMAIL_WEBMASTER,
            'password' => $contrasena,
            'is_active' => true,
        ]);
        $usuario->forceFill(['email_verified_at' => now()])->save();
        $usuario->syncRoles(['administrador']);

        $this->info('Cuenta creada: '.User::EMAIL_WEBMASTER.' (rol administrador, protegida: no se puede eliminar ni desactivar).');

        if ($this->option('generar')) {
            $this->warn("Contraseña generada (guardala ahora, no se vuelve a mostrar): {$contrasena}");
        }

        return self::SUCCESS;
    }
}
