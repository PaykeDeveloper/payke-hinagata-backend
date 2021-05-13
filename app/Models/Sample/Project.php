<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Division\Division;
use App\Models\Traits\OptimisticLocking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model
{
    use HasFactory;
    use OptimisticLocking;

    public const RESOURCE = 'project';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'lock_version' => 1,
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public static function createFromRequest(mixed $attributes, Division $division): self
    {
        $project = new Project();
        $project->fill($attributes);
        $project->division_id = $division->id;
        $project->save();
        return $project;
    }
}
