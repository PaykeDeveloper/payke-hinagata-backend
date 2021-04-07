<?php

namespace App\Models\Traits;

trait UserAllPermissions
{
    /**
     * hasPermissionTo の拡張
     * {action}_{model} に加えて {action}All_{model} のパーミッションも考慮する
     */
    public function hasAllOrPermissionTo(string $action, $model)
    {
        if ($this->hasPermissionTo($action . '_' . $model)) {
            // 通常権限
            return true;
        } elseif ($this->hasPermissionTo($action . 'All_' . $model)) {
            // All 権限
            return true;
        }
        return false;
    }
}
