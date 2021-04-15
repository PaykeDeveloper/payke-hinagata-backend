<?php

namespace App\Policies\Common;

class AuthorizablePolicy
{
    /** @var string */
    protected $model;

    public function __construct()
    {
        if (!$this->model) {
            throw new \Exception('model クラスを指定してください');
        }
    }

    public function modelName(): string
    {
        $modelName = $this->model;
        return strtolower(class_basename($modelName));
    }
}
