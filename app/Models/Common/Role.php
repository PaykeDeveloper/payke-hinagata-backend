<?php

namespace App\Models\Common;

use App\Models\Division\Member;
use App\Models\Division\MemberRole;
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

    protected $appends = ['type', 'required'];

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

    public function getRequiredAttribute(): bool
    {
        $name = $this->name;
        if (in_array($name, UserRole::required())) {
            return true;
        }
        if (in_array($name, MemberRole::required())) {
            return true;
        }
        return false;
    }
}
