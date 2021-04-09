<?php

namespace App\Models;

use App\Models\Common\AuthableModel;
use App\Models\Sample\Staff;
use App\Models\Traits\HasAllOrPermissions;
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
    use HasAllOrPermissions;

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

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function companies()
    {
        // get all company ids belonging to the user
        $companies = [];
        foreach ($this->staff()->get() as $staff) {
            $companies[] = $staff->company;
        }
        // remove duplicates
        $unique_companies = [];
        foreach ($companies as $company) {
            $unique_companies[$company->id] = $company;
        }
        return array_values($unique_companies);
    }
}
