<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use QCod\ImageUp\Exceptions\InvalidUploadFieldException;
use QCod\ImageUp\HasImageUploads;

// FIXME: サンプルコードです。

/**
 * @mixin IdeHelperBook
 */
class Book extends Model
{
    use HasFactory;
    use HasImageUploads;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected static $imageFields = ['cover'];
    protected $hidden = ['cover'];
    protected $appends = ['cover_url'];

    public function comments(): HasMany
    {
        return $this->hasMany(BookComment::class);
    }

    /**
     * @return string|null
     * @throws InvalidUploadFieldException
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover) {
            return null;
        }
        return $this->imageUrl('cover');
    }
}
