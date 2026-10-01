<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class FamiliaPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'familias';
    }
}
