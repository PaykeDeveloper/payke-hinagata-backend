<?php

namespace App\Models\Traits;

use App\Models\Common\PermissionType;
use Spatie\Permission\Traits\HasRoles;

trait HasAuthorization
{
    use HasRoles;

    /**
     * hasPermissionTo の拡張
     * {action}_{model} に加えて {action}All_{model} のパーミッションも考慮する
     */
    public function hasAllOrPermissionTo(string $action, string $resource): bool
    {
        if ($this->hasPermissionTo($action . '_' . $resource)) {
            // 通常権限
            return true;
        } elseif ($this->hasPermissionTo($action . 'All_' . $resource)) {
            // All 権限
            return true;
        }
        return false;
    }

    private function hasResourcePermissionTo(string $type, string $resource): bool
    {
        return $this->hasPermissionTo(PermissionType::getName($type, $resource));
    }

    public function hasViewPermissionTo(string $resource): bool
    {
        return $this->hasOwnViewPermissionTo($resource) || $this->hasAllViewPermissionTo($resource);
    }

    public function hasOwnViewPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::VIEW, $resource);
    }

    public function hasAllViewPermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::VIEW_ALL, $resource);
    }

    public function hasCreatePermissionTo(string $resource): bool
    {
        return $this->hasOwnCreatePermissionTo($resource) || $this->hasAllCreatePermissionTo($resource);
    }

    public function hasOwnCreatePermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::CREATE, $resource);
    }

    public function hasAllCreatePermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::CREATE_ALL, $resource);
    }

    public function hasUpdatePermissionTo(string $resource): bool
    {
        return $this->hasOwnUpdatePermissionTo($resource) || $this->hasAllUpdatePermissionTo($resource);
    }

    public function hasOwnUpdatePermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::UPDATE, $resource);
    }

    public function hasAllUpdatePermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::UPDATE_ALL, $resource);
    }

    public function hasDeletePermissionTo(string $resource): bool
    {
        return $this->hasOwnDeletePermissionTo($resource) || $this->hasAllDeletePermissionTo($resource);
    }

    public function hasOwnDeletePermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::DELETE, $resource);
    }

    public function hasAllDeletePermissionTo(string $resource): bool
    {
        return $this->hasResourcePermissionTo(PermissionType::DELETE_ALL, $resource);
    }
}
