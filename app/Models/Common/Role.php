<?php

namespace App\Models\Common;

use App\Models\Sample\Member;
use App\Models\Sample\MemberRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseModel;

/**
 * @mixin IdeHelperRole
 */
class Role extends BaseModel
{
    use HasFactory;

    public const RESOURCE = 'role';

    protected $hidden = ['permissions', 'pivot', 'guard_name', 'created_at', 'updated_at'];

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
