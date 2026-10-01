<?php

namespace App\Models;

use Database\Factories\UserFactory;
use DomainException;
use Filament\Auth\MultiFactor\Email\Concerns\InteractsWithEmailAuthentication;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasEmailAuthentication
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, LogsActivity, Notifiable, SoftDeletes;

    use InteractsWithEmailAuthentication;

    /** Cuenta de mantenimiento de WebParaguay (quien desarrolló y mantiene el sitio). */
    public const EMAIL_WEBMASTER = 'webmaster@webparaguay.com';

    /**
     * Cuentas que no se pueden eliminar, desactivar ni cambiar de correo. La protección vive en el modelo, no en una
     * pantalla en particular, para que valga igual desde el panel, `tinker`, un seeder o un comando.
     * (Un `User::where(...)->delete()` masivo no dispara eventos de modelo: no usarlo sobre usuarios.)
     *
     * @var array<int, string>
     */
    public const PROTECTED_EMAILS = [self::EMAIL_WEBMASTER];

    protected static function booted(): void
    {
        static::deleting(function (User $usuario): void {
            if ($usuario->isProtected()) {
                throw new DomainException('Esta cuenta de mantenimiento no se puede eliminar.');
            }
        });

        static::updating(function (User $usuario): void {
            if (! $usuario->isProtected()) {
                return;
            }

            if ($usuario->isDirty('email')) {
                throw new DomainException('El correo de la cuenta de mantenimiento no se puede cambiar.');
            }

            if ($usuario->isDirty('is_active') && ! $usuario->is_active) {
                throw new DomainException('La cuenta de mantenimiento no se puede desactivar.');
            }
        });
    }

    /** ¿Es una cuenta de mantenimiento protegida? Se mira el correo original: cambiarlo en memoria no la desprotege. */
    public function isProtected(): bool
    {
        return in_array(mb_strtolower((string) ($this->getOriginal('email') ?? $this->email)), self::PROTECTED_EMAILS, true);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
