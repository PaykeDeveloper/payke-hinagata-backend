<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseModel;

/**
 * @mixin IdeHelperRole
 */
class Role extends BaseModel
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'guard_name'];
}
