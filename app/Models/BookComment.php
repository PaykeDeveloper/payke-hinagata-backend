<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// FIXME: サンプルコードです。

/**
 * @mixin IdeHelperBookComment
 */
class BookComment extends Model
{
    use HasFactory;
    use UsesUuid;

    /**
     * Eloquentの規約は大事。。
     * https://readouble.com/laravel/8.x/ja/eloquent.html
     */
//    public $incrementing = false;
//    protected $keyType = 'string';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Mutatorはその何倍も大事。。
     * https://readouble.com/laravel/8.x/ja/eloquent-mutators.html
     */
    protected $dates = [
//        'publish_date',
        'approved_at',
    ];
    protected $casts = [
        'confirmed' => 'boolean',
        'amount' => 'double',
    ];

//    /**
//     * URLのキーをID以外に設定したい場合はここで指定する。
//     * @return string
//     */
//    public function getRouteKeyName(): string
//    {
//        return 'slug';
//    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
