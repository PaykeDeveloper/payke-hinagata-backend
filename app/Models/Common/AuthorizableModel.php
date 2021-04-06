<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuthorizableModel extends Model
{
    use HasFactory;

    protected static $parentModel;

    public static function getParentModel()
    {
        return static::$parentModel;
    }

    public static function permissionModels()
    {
        $models = [static::baseName(static::class)];

        // 親子関係が設定されてなければ自分自身のみ
        if (!static::$parentModel) {
            return $models;
        }

        // 親子関係が設定されている場合は全ての親を返す
        $targetModel = static::$parentModel;
        while ($targetModel) {
            if (!is_subclass_of($targetModel, AuthorizableModel::class)) {
                break;
            }

            array_push($models, static::baseName($targetModel));
            $targetModel = $targetModel::$parentModel;
        }

        return $models;
    }

    protected static function baseName(string $model)
    {
        return strtolower(basename(strtr($model, '\\', '/')));
    }

    /**
     * 全閲覧権限がある場合は全て、それ以外は userId で絞り込む
     */
    public static function allOrWhereUserId(User $user)
    {
        if ($user->can('viewAnyAll_' . strtolower(basename(strtr(static::class, '\\', '/'))))) {
            // 全ての閲覧権限を持っている場合は全データ取得
            $data = static::all();
        } elseif ($user->can('viewAnyAll_' . strtolower(basename(strtr(static::$parentModel, '\\', '/'))))) {
            // 親が設定されている場合は親の権限を確認して権限がある場合も全データ取得
            $data = static::all();
        } else {
            $data = static::whereUserId($user->id)->get();
        }

        return $data;
    }
}
