<?php

namespace App\Models;

use App\Models\Common\AuthableModel;
use App\Models\Traits\UserAllPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends AuthableModel
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use UserAllPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // FIXME: SAMPLE CODE

    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($check) {
            foreach ($check->books as $book) {
                $book->delete();
            }
        });
    }

    public function books(): HasMany
    {
        return $this->hasMany(\App\Models\Sample\Book::class);
    }
}
