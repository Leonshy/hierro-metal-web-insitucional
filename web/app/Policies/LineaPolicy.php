<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class LineaPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'familias';
    }
}
