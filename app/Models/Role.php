<?php

namespace App\Models;

use App\Models\Common\MemberRole;
use App\Models\Common\UserRole;
use App\Models\Sample\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseModel;

/**
 * @mixin IdeHelperRole
 */
class Role extends BaseModel
{
    use HasFactory;

    public const RESOURCE = 'role';

    protected $hidden = ['created_at', 'updated_at', 'guard_name'];

    protected $appends = ['type'];

    public function getTypeAttribute(): ?string
    {
        $name = $this->name;
        if (in_array($name, UserRole::all())) {
            return User::RESOURCE;
        }
        if (in_array($name, MemberRole::all())) {
            return Member::RESOURCE;
        }
        return null;
    }
}
