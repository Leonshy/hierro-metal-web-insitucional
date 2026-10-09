<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class NovedadPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'novedades';
    }
}
