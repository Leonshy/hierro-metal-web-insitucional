<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class VendedorPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'vendedores';
    }
}
