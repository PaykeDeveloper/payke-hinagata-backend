<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Common\AuthorizableModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperBook
 */
class Book extends AuthorizableModel
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($check) {
            foreach ($check->comments as $comment) {
                $comment->delete();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BookComment::class);
    }

    public static function createWithUser(mixed $attributes, User $user): Book
    {
        $book = new self();
        $book->fill($attributes);
        $book->user_id = $user->id;
        $book->save();
        return $book;
    }
}
