<?php

namespace App\Models\Traits;

use App\Models\User;

/**
 * laravel-permission を自動的に生成する trait
 *
 * allOrWhereXXX の実装 (whereXXX の派生)
 * モデルと権限の階層化
 */
trait AllOrWhere
{
    public static function permissionModels()
    {
        return [static::baseName(static::class)];
    }

    protected static function baseName(string $model)
    {
        return strtolower(basename(strtr($model, '\\', '/')));
    }

    /**
     * 全閲覧権限がある場合は全て、それ以外は userId で絞り込む
     */
    private static function allOrWhere(string $name, $arguments)
    {
        if (!is_a($arguments[0], User::class)) {
            throw new \Exception('第一引数には User モデルを指定してください');
        }

        $user = $arguments[0];
        $action = 'where' . str_replace(__FUNCTION__, '', $name);

        if ($user->can('viewAnyAll_' . strtolower(basename(strtr(static::class, '\\', '/'))))) {
            // 全ての閲覧権限を持っている場合は全データ取得
            $data = static::all();
        } elseif ($user->can('viewAnyAll_' . strtolower(basename(strtr(static::$parentModel, '\\', '/'))))) {
            // 親が設定されている場合は親の権限を確認して権限がある場合も全データ取得
            $data = static::all();
        } else {
            $data = parent::{$action}($user->id)->get();
        }

        return $data;
    }

    public static function __callStatic($name, $arguments)
    {
        // allOrWhere で始まる場合は allOrWhere メソッドとして呼び出す
        if (str_starts_with($name, 'allOrWhere')) {
            return static::allOrWhere($name, $arguments);
        } else {
            // それ以外はそのままコールし直す
            return parent::{$name}(...$arguments);
        }
    }
}
