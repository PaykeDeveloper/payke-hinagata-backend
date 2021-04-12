<?php

namespace App\Policies\Common;

use App\Models\User;
use Spatie\Permission\Models\Permission;

class AuthorizablePolicy
{
    protected $model;

    public function __construct()
    {
        if (!$this->model) {
            throw new \Exception('model クラスを指定してください');
        }
    }

    public function baseName(string $model)
    {
        $modelName = $model ?? $this->model;
        return strtolower(basename(strtr($modelName, '\\', '/')));
    }
}
