<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @mixin IdeHelperBookComment
 */
class BookComment extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * デフォルトの設定
     *
     * @var string[]
     */
    protected $attributes = [
        'description' => ''
    ];

    /**
     * Eloquentの規約は大事。。
     * https://readouble.com/laravel/8.x/ja/eloquent.html
     */
//    public $incrementing = false;
//    protected $keyType = 'string';
    protected $guarded = [
        'id',
        'slug',
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

    /**
     * 画像アップロード用の設定
     */
    private const COLLECTION_NAME = 'cover';
    protected $hidden = [self::COLLECTION_NAME];
    protected $appends = ['cover_url'];

    /**
     * URLのキーをID以外に設定したい場合はここで指定する。
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * 画像アップロード用の設定
     * @return string|null
     */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::COLLECTION_NAME);
    }

    public function setCoverAttribute(?UploadedFile $value): void
    {
        if ($value) {
            $this->addMedia($value)
                ->toMediaCollection(self::COLLECTION_NAME);
        } else {
            $this->clearMediaCollection(self::COLLECTION_NAME);
        }
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection(self::COLLECTION_NAME)
            ->singleFile();
    }

    public static function createWithBook(mixed $attributes, Book $book): BookComment
    {
        $comment = new self();
        $comment->fill($attributes);
        $comment->book_id = $book->id;
        $comment->slug = (string)Str::uuid();
        $comment->save();
        return $comment;
    }
}
