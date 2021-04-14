<?php

namespace App\Models;

use App\Models\Sample\Employee;
use App\Models\Traits\AllOrWhereable;
use App\Models\Traits\HasAllOrPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use HasAllOrPermissions, AllOrWhereable;

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

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function companies()
    {
        // get all company ids belonging to the user
        $companies = [];
        foreach ($this->employees()->get() as $employee) {
            $companies[] = $employee->company;
        }
        // remove duplicates
        $unique_companies = [];
        foreach ($companies as $company) {
            $unique_companies[$company->id] = $company;
        }
        return array_values($unique_companies);
    }
}
