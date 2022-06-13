<?php

namespace App\Models\Traits;

use App\Models\Common\PermissionType;
use App\Models\ModelType;
use Spatie\Permission\Traits\HasRoles;

trait HasAuthorization
{
    use HasRoles;

    private function hasResourcePermissionTo(ModelType $model, PermissionType $permission): bool
    {
        return $this->hasPermissionTo(PermissionType::getName($model, $permission));
    }

    public function hasViewPermissionTo(ModelType $model): bool
    {
        return $this->hasViewOwnPermissionTo($model) || $this->hasViewAllPermissionTo($model);
    }

    public function hasViewOwnPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::viewOwn);
    }

    public function hasViewAllPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::viewAll);
    }

    public function hasCreatePermissionTo(ModelType $model): bool
    {
        return $this->hasCreateOwnPermissionTo($model) || $this->hasCreateAllPermissionTo($model);
    }

    public function hasCreateOwnPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::createOwn);
    }

    public function hasCreateAllPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::createAll);
    }

    public function hasUpdatePermissionTo(ModelType $model): bool
    {
        return $this->hasUpdateOwnPermissionTo($model) || $this->hasUpdateAllPermissionTo($model);
    }

    public function hasUpdateOwnPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::updateOwn);
    }

    public function hasUpdateAllPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::updateAll);
    }

    public function hasDeletePermissionTo(ModelType $model): bool
    {
        return $this->hasDeleteOwnPermissionTo($model) || $this->hasDeleteAllPermissionTo($model);
    }

    public function hasDeleteOwnPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::deleteOwn);
    }

    public function hasDeleteAllPermissionTo(ModelType $model): bool
    {
        return $this->hasResourcePermissionTo($model, PermissionType::deleteAll);
    }
}
