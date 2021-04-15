<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'updated_at',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * 指定したユーザーの Member を取得
     */
    public function findMembersByUser(User $user): Collection
    {
        return $this->division->members()->where('user_id', $user->id)->get();
    }

    public static function createWithDivision(Division $division, array $attributes): Project
    {
        $project = new Project();
        $project->fill($attributes);
        $division->projects()->save($project);
        return $project;
    }
}
