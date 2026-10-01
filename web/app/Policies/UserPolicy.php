<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesViaPermissions;

class UserPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'users';
    }

    /** Nadie, ni un administrador, puede eliminar una cuenta de mantenimiento protegida. */
    public function delete(User $user, $model): bool
    {
        return ! $model->isProtected() && $user->can('users.delete');
    }
}
