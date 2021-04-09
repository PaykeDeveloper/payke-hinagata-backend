<?php

namespace App\Models\Traits;

trait HasAllOrPermissions
{
    /**
     * hasPermissionTo の拡張
     * {action}_{model} に加えて {action}All_{model} のパーミッションも考慮する
     */
    public function hasAllOrPermissionTo(string $action, $resource)
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
}
