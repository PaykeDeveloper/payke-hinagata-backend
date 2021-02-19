<?php

namespace App\Models;

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

    /**
     * Eloquentの規約は大事。。
     * https://readouble.com/laravel/8.x/ja/eloquent.html
     */
    public $incrementing = false;
    protected $keyType = 'string';
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

    public static function boot()
    {
        parent::boot();
        
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
