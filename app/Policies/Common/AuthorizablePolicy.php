<?php

namespace App\Policies\Common;

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
