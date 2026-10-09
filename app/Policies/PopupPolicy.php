<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class PopupPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'popups';
    }
}
