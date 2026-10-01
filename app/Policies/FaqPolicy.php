<?php

namespace App\Policies;

use App\Policies\Concerns\AuthorizesViaPermissions;

class FaqPolicy
{
    use AuthorizesViaPermissions;

    protected function permissionPrefix(): string
    {
        return 'faqs';
    }
}
