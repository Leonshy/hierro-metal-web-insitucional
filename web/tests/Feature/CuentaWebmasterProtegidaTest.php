<?php

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

function crearWebmaster(): User
{
    test()->artisan('usuarios:crear-webmaster', ['--generar' => true])->assertSuccessful();

    return User::query()->where('email', User::EMAIL_WEBMASTER)->firstOrFail();
}

function adminComun(): User
{
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('administrador');

    return $admin;
}

// ---- El comando que crea la cuenta ----------------------------------------------------------------------

it('crea la cuenta de mantenimiento con rol administrador, activa y verificada', function () {
    $webmaster = crearWebmaster();

    expect($webmaster->email)->toBe('webmaster@webparaguay.com')
        ->and($webmaster->hasRole('administrador'))->toBeTrue()
        ->and($webmaster->is_active)->toBeTrue()
        ->and($webmaster->email_verified_at)->not->toBeNull()
        ->and($webmaster->canAccessPanel(Filament\Facades\Filament::getPanel('admin')))->toBeTrue();
});

it('muestra la contraseña generada una sola vez y la guarda cifrada', function () {
    $this->artisan('usuarios:crear-webmaster', ['--generar' => true])
        ->expectsOutputToContain('Contraseña generada')
        ->assertSuccessful();

    $webmaster = User::query()->where('email', User::EMAIL_WEBMASTER)->first();

    expect(mb_strlen($webmaster->password))->toBeGreaterThan(40)->and($webmaster->password)->toStartWith('$2y$');
});

it('es seguro volver a correrlo: si la cuenta existe no cambia la contraseña ni nada', function () {
    $webmaster = crearWebmaster();
    $webmaster->update(['password' => 'Contraseña-elegida-por-webparaguay']);
    $hash = $webmaster->fresh()->password;

    $this->artisan('usuarios:crear-webmaster', ['--generar' => true])
        ->expectsOutputToContain('ya existe')
        ->doesntExpectOutputToContain('Contraseña generada')
        ->assertSuccessful();

    expect($webmaster->fresh()->password)->toBe($hash)
        ->and(Hash::check('Contraseña-elegida-por-webparaguay', $webmaster->fresh()->password))->toBeTrue()
        ->and(User::query()->where('email', User::EMAIL_WEBMASTER)->count())->toBe(1);
});

it('pide la contraseña por pantalla (sin mostrarla) cuando no se usa --generar', function () {
    $this->artisan('usuarios:crear-webmaster')
        ->expectsQuestion('Contraseña de la cuenta de mantenimiento (no se muestra)', 'una-contraseña-larga-y-propia')
        ->assertSuccessful();

    expect(Hash::check('una-contraseña-larga-y-propia', User::query()->where('email', User::EMAIL_WEBMASTER)->value('password')))->toBeTrue();
});

it('falla con un mensaje claro si todavía no existe el rol administrador', function () {
    Role::query()->delete();

    $this->artisan('usuarios:crear-webmaster', ['--generar' => true])
        ->expectsOutputToContain('Falta el rol')
        ->assertFailed();

    expect(User::query()->where('email', User::EMAIL_WEBMASTER)->exists())->toBeFalse();
});

// ---- La protección (modelo) -------------------------------------------------------------------------------

it('no se puede eliminar, ni a medias (soft delete) ni del todo (force delete)', function () {
    $webmaster = crearWebmaster();

    expect(fn () => $webmaster->delete())->toThrow(DomainException::class, 'no se puede eliminar');
    expect(fn () => $webmaster->forceDelete())->toThrow(DomainException::class);
    expect(User::query()->where('email', User::EMAIL_WEBMASTER)->exists())->toBeTrue()
        ->and(User::withTrashed()->where('email', User::EMAIL_WEBMASTER)->first()->trashed())->toBeFalse();
});

it('no se puede desactivar ni cambiar su correo, pero sí su nombre y su contraseña', function () {
    $webmaster = crearWebmaster();

    // Cada intento rechazado deja el objeto en memoria con el valor sucio: se parte de un registro limpio cada vez.
    expect(fn () => $webmaster->fresh()->update(['is_active' => false]))->toThrow(DomainException::class, 'desactivar');
    expect(fn () => $webmaster->fresh()->update(['email' => 'otro@ejemplo.com']))->toThrow(DomainException::class, 'correo');

    $webmaster->fresh()->update(['name' => 'Otro nombre', 'password' => 'nueva-contraseña-123']);
    expect($webmaster->fresh()->name)->toBe('Otro nombre')
        ->and($webmaster->fresh()->is_active)->toBeTrue()
        ->and($webmaster->fresh()->email)->toBe('webmaster@webparaguay.com');
});

it('cambiar el correo en memoria no le quita la protección', function () {
    $webmaster = crearWebmaster();
    $webmaster->email = 'camuflado@ejemplo.com';

    expect($webmaster->isProtected())->toBeTrue()
        ->and(fn () => $webmaster->delete())->toThrow(DomainException::class);
});

it('la protección no distingue mayúsculas del correo', function () {
    $usuario = User::factory()->make(['email' => 'WebMaster@WebParaguay.com']);

    expect($usuario->isProtected())->toBeTrue();
});

it('un usuario común sigue pudiéndose eliminar y desactivar', function () {
    $comun = User::factory()->create(['email' => 'persona@ejemplo.com', 'is_active' => true]);

    $comun->update(['is_active' => false]);
    $comun->delete();

    expect(User::withTrashed()->where('email', 'persona@ejemplo.com')->first()->trashed())->toBeTrue()
        ->and($comun->isProtected())->toBeFalse();
});

// ---- La política y el panel ----------------------------------------------------------------------------------

it('ni un administrador puede eliminar la cuenta de mantenimiento, pero sí a otros usuarios', function () {
    $webmaster = crearWebmaster();
    $admin = adminComun();
    $otro = User::factory()->create();

    expect($admin->can('delete', $webmaster))->toBeFalse()
        ->and($admin->can('delete', $otro))->toBeTrue();
});

it('aparece en la lista de usuarios, marcada como cuenta de mantenimiento', function () {
    crearWebmaster();
    $this->actingAs(adminComun());

    $this->livewire(ListUsers::class)
        ->assertCanSeeTableRecords(User::query()->where('email', User::EMAIL_WEBMASTER)->get())
        ->assertSee('webmaster@webparaguay.com')
        ->assertSee('Cuenta de mantenimiento · no se puede eliminar');
});

it('en su pantalla de edición no hay botón de eliminar y no se puede cambiar el correo, el rol ni desactivarla', function () {
    $webmaster = crearWebmaster();
    $this->actingAs(adminComun());

    $this->livewire(EditUser::class, ['record' => $webmaster->getKey()])
        ->assertSuccessful()
        ->assertActionHidden(DeleteAction::class)
        ->assertFormFieldDisabled('email')
        ->assertFormFieldDisabled('roles')
        ->assertFormFieldDisabled('is_active');
});

it('en la pantalla de edición de un usuario común el botón de eliminar sí está', function () {
    $comun = User::factory()->create();
    $this->actingAs(adminComun());

    $this->livewire(EditUser::class, ['record' => $comun->getKey()])
        ->assertActionVisible(DeleteAction::class)
        ->assertFormFieldEnabled('email');
});

it('el administrador del cliente puede editar el nombre de la cuenta de mantenimiento desde el panel sin romper nada', function () {
    $webmaster = crearWebmaster();
    $this->actingAs(adminComun());

    $this->livewire(EditUser::class, ['record' => $webmaster->getKey()])
        ->fillForm(['name' => 'Soporte WebParaguay'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($webmaster->fresh()->name)->toBe('Soporte WebParaguay')
        ->and($webmaster->fresh()->is_active)->toBeTrue()
        ->and($webmaster->fresh()->hasRole('administrador'))->toBeTrue();
});
