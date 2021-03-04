<?php

namespace App\Models\Sample;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BookComment::class);
    }

    public static function createWithUser(array $attributes, User $user): Book
    {
        $book = new self();
        $book->fill($attributes);
        $book->user_id = $user->id;
        $book->save();
        return $book;
    }
}
