<?php

namespace App\Models\Traits;

use App\Models\Common\PermissionType;
use Spatie\Permission\Traits\HasRoles;

trait HasAuthorization
{
    use HasRoles;

    private function hasResourcePermissionTo(string $type, string $resource): bool
    {
        return $this->hasPermissionTo(PermissionType::getName($type, $resource));
    }

    public function hasViewPermissionTo(string $resource): bool
    {
        return $this->hasViewOwnPermissionTo($resource) || $this->hasViewAllPermissionTo($resource);
    }

    public function hasViewOwnPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::VIEW_OWN, $resource);
    }

    public function hasViewAllPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::VIEW_ALL, $resource);
    }

    public function hasCreatePermissionTo(string $resource): bool
    {
        return $this->hasCreateOwnPermissionTo($resource) || $this->hasCreateAllPermissionTo($resource);
    }

    public function hasCreateOwnPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::CREATE_OWN, $resource);
    }

    public function hasCreateAllPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::CREATE_ALL, $resource);
    }

    public function hasUpdatePermissionTo(string $resource): bool
    {
        return $this->hasUpdateOwnPermissionTo($resource) || $this->hasUpdateAllPermissionTo($resource);
    }

    public function hasUpdateOwnPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::UPDATE_OWN, $resource);
    }

    public function hasUpdateAllPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::UPDATE_ALL, $resource);
    }

    public function hasDeletePermissionTo(string $resource): bool
    {
        return $this->hasDeleteOwnPermissionTo($resource) || $this->hasDeleteAllPermissionTo($resource);
    }

    public function hasDeleteOwnPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::DELETE_OWN, $resource);
    }

    public function hasDeleteAllPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::DELETE_ALL, $resource);
    }
}
