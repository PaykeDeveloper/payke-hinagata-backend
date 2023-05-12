<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\ModelFilters\Sample\ProjectFilter;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Traits\OptimisticLocking;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model implements HasMedia
{
    use HasFactory;
    use OptimisticLocking;

    /**
     * デフォルトの設定
     */
    protected $attributes = [
        'description' => '',
        'lock_version' => 1,
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
    protected $casts = [
        'finished_at' => 'datetime',
        'approved' => 'boolean',
        'coefficient' => 'double',
        'priority' => Priority::class,
    ];

    protected $appends = ['cover_url'];

    protected static function booted()
    {
        self::creating(function (self $project) {
            if (is_null($project->getAttributeValue('slug'))) {
                $project->slug = Str::uuid()->toString();
            }
        });
    }

    /**
     * URLのキーをID以外に設定したい場合はここで指定する。
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * 画像アップロード用の設定
     */
    use InteractsWithMedia;

    private const COLLECTION_NAME = 'cover';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COLLECTION_NAME)->singleFile();
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::COLLECTION_NAME);
    }

    public function saveCover(?UploadedFile $value): void
    {
        if ($value) {
            $this->addMedia($value)->toMediaCollection(self::COLLECTION_NAME);
        } else {
            $this->clearMediaCollection(self::COLLECTION_NAME);
        }
    }

    /**
     * APIフィルター用の設定
     */
    use Filterable;

    public function modelFilter(): ?string
    {
        return $this->provideFilter(ProjectFilter::class);
    }
}
