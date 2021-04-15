<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as ModelsPermission;

/**
 * @mixin IdeHelperPermission
 */
class Permission extends ModelsPermission
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'guard_name'];
}
