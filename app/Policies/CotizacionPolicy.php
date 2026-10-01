<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class CotizacionPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'cotizaciones';
    }
}
