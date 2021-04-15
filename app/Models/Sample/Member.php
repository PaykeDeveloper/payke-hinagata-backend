<?php

// FIXME: SAMPLE CODE

namespace App\Models\Sample;

use App\Models\Traits\HasAllOrPermissions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperMember
 */
class Member extends Model
{
    use HasFactory, HasRoles, HasAllOrPermissions;

    /** @var string */
    protected $guard_name = 'web';

    protected $guarded = [
        'id',
        'updated_at',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public static function findByUniqueKeys(string $user_id, string $division_id): ?Member
    {
        return self::where('user_id', $user_id)->where('division_id', $division_id)->first();
    }

    public static function createWithUserAndDivision(User $user, Division $division, array $attributes = []): Member
    {
        $member = new self();
        $member->fill($attributes);
        $member->user_id = $user->id;
        $member->division_id = $division->id;
        $member->save();
        return $member;
    }
}
