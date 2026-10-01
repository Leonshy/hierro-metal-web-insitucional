<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class PasoPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'pasos';
    }
}
