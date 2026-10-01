<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class RubroPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'rubros';
    }
}
