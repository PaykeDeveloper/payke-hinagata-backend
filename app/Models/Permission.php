<?php

namespace App\Models;

use App\Models\Traits\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasFactory, Authorizable;
}
