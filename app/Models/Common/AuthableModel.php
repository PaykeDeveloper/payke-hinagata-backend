<?php

namespace App\Models\Common;

use App\Models\Traits\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Authenticatable 用の Authable 実装の継承用クラス
 */
class AuthableModel extends Authenticatable
{
    use Authorizable;
}
