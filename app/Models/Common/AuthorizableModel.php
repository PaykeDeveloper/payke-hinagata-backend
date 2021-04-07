<?php

namespace App\Models\Common;

use App\Models\Traits\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Authable 実装の継承用クラス
 * 
 * パーミッション付きの通常モデルはこれを継承させる
 */
class AuthorizableModel extends Model
{
    use Authorizable;
}
