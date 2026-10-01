<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class ServicioPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'servicios';
    }
}
