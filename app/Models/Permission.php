<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as BaseModel;

/**
 * @mixin IdeHelperPermission
 */
class Permission extends BaseModel
{
    use HasFactory;

    public const RESOURCE = 'permission';

    protected $hidden = ['created_at', 'updated_at', 'guard_name'];
}
