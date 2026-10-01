<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class DiferencialPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'diferenciales';
    }
}
