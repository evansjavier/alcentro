<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const ROLE_OWNER = 'owner';
    public const ROLE_ADMIN = 'admin';
}
