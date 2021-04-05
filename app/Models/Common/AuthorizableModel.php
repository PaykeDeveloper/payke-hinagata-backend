<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuthorizableModel extends Model
{
    use HasFactory;

    /**
     * 全閲覧権限がある場合は全て、それ以外は userId で絞り込む
     */
    public static function allOrWhereUserId(User $user)
    {
        if ($user->can('viewAnyAll_' . strtolower(basename(strtr(static::class, '\\', '/'))))) {
            // 全ての閲覧権限を持っている場合は無条件でパス
            $books = static::all();
        } else {
            $books = static::whereUserId($user->id)->get();
        }

        return $books;
    }
}
