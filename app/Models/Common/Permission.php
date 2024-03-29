<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as BaseModel;

/**
 * @mixin IdeHelperPermission
 */
class Permission extends BaseModel
{
    use HasFactory;
}
