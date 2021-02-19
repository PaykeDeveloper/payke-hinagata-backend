<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// FIXME: サンプルコードです。
/**
 * @mixin IdeHelperBook
 */
class Book extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(BookComment::class);
    }
}
