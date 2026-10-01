<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class HorarioPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'horarios';
    }
}
