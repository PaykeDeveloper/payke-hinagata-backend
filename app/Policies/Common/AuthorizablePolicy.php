<?php

namespace App\Policies\Common;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class AuthorizablePolicy
{
    private $base_permissions = [
        'viewAny', // index
        'viewAnyAll', // can view all resources
        'view',    // show
        'create',  // store
        'update',  // update
        'delete',  // destory
    ];

    protected $model;

    public function __construct()
    {
        if (!$this->model) {
            throw new \Exception('model クラスを指定してください');
        }

        foreach ($this->base_permissions as $permission) {
            try {
                $base_name = str_replace('Policy', '', strtolower(basename(strtr($this->model, '\\', '/'))));
                Permission::create([
                    'name' => $permission . '_' . $base_name,
                    'guard_name' => 'web',
                ]);
            } catch (\Exception $e) {
            }
        }
    }

    public function baseName()
    {
        return strtolower(basename(strtr($this->model, '\\', '/')));
    }

    /**
     * モデルのパーミッションを追加
     */
    public function createPermission($action)
    {
        $base_name = str_replace('Policy', '', strtolower(basename(strtr(Book::class, '\\', '/'))));

        try {
            Permission::create([
                'name' => $action . '_' . $base_name,
                'guard_name' => 'web',
            ]);
        } catch (\Exception $e) {
        }
    }
}
