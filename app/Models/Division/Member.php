<?php

// FIXME: SAMPLE CODE

namespace App\Models\Division;

use App\Models\Traits\HasAuthorization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * @mixin IdeHelperMember
 */
class Member extends Model
{
    use HasFactory, HasAuthorization;

    protected string $guard_name = 'web';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function getAllPermissionNames(): Collection
    {
        return $this->getAllPermissions()->pluck('name');
    }
}
