<?php

namespace App\Models\Common;

use App\Models\Division\MemberRole;
use App\Models\ModelType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseModel;

/**
 * @mixin IdeHelperRole
 */
class Role extends BaseModel
{
    use HasFactory;

    public function getTypeAttribute(): ?ModelType
    {
        $name = $this->name;
        if (in_array($name, UserRole::all())) {
            return ModelType::user;
        }
        if (in_array($name, MemberRole::all())) {
            return ModelType::member;
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
