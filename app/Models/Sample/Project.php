<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Division\Division;
use App\Models\Traits\OptimisticLocking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use OptimisticLocking;

    public const RESOURCE = 'project';

    protected $hidden = [
        'division',
        'media',
    ];

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
    protected $dates = [
        'finished_at',
    ];
    protected $casts = [
        'approved' => 'boolean',
        'coefficient' => 'double',
    ];

    /**
     * 画像アップロード用の設定
     */
    private const COLLECTION_NAME = 'cover';
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

    /**
     * 画像アップロード用の設定
     * @return string|null
     */
    public function getCoverUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::COLLECTION_NAME);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function saveCover(?UploadedFile $value): void
    {
        if ($value) {
            $this->addMedia($value)->toMediaCollection(self::COLLECTION_NAME);
        } else {
            $this->clearMediaCollection(self::COLLECTION_NAME);
        }
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COLLECTION_NAME)->singleFile();
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public static function createFromRequest(mixed $attributes, Division $division): self
    {
        $project = new self();
        $project->fill($attributes);
        $project->division_id = $division->id;
        $project->save();
        if (array_key_exists('cover', $attributes)) {
            $project->saveCover($attributes['cover']);
        }
        return $project->fresh();
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function updateFromRequest(mixed $attributes): self
    {
        $this->update($attributes);
        if (array_key_exists('cover', $attributes)) {
            $this->saveCover($attributes['cover']);
        }
        return $this->fresh();
    }
}
