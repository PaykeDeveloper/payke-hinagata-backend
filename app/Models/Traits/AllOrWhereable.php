<?php

namespace App\Models\Traits;

use App\Models\User;

/**
 * Model へ allOrWhereXXX の実装を追加する
 * (whereXXX の派生)
 *
 * view か viewAny を持っているかどうかで
 * 一覧を全て取得するのか
 * 所属しているリソースのみの一覧を取得するのか
 * を自動的に判定する
 */
trait AllOrWhereable
{
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
