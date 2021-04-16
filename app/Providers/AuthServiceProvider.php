<?php

namespace App\Providers;

use App\Models\User;
use Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\Common\UserPolicy::class,
        \App\Models\Common\Permission::class => \App\Policies\Common\PermissionPolicy::class,
        \App\Models\Common\Role::class => \App\Policies\Common\RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $origin = config('constant.frontend_origin');
            return "$origin/reset-password?token=$token";
        });

        VerifyEmail::createUrlUsing(function (User $user) {
            $origin = config('constant.frontend_origin');
            $base_url = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );

            $path = null;
            $query = null;
            $url = parse_url($base_url);
            if (is_array($url)) {
                if (array_key_exists('path', $url)) {
                    $path = $url['path'];
                }
                if (array_key_exists('query', $url)) {
                    $query = $url['query'];
                }
            }
            return "{$origin}{$path}?{$query}";
        });
    }
}
