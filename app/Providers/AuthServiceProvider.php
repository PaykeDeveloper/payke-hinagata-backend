<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\User::class => \App\Policies\Common\UserPolicy::class,
        \App\Models\Common\Permission::class => \App\Policies\Common\PermissionPolicy::class,
        \App\Models\Common\Role::class => \App\Policies\Common\RolePolicy::class,
        \App\Models\Common\Invitation::class => \App\Policies\Common\InvitationPolicy::class,
        \App\Models\Division\Division::class => \App\Policies\Division\DivisionPolicy::class,
        \App\Models\Division\Member::class => \App\Policies\Division\MemberPolicy::class,

        // FIXME: SAMPLE CODE
        \App\Models\Sample\Project::class => \App\Policies\Sample\ProjectPolicy::class,
    ];

    protected array $exceptedPolicies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /** @see \Illuminate\Auth\Access\Gate::guessPolicyName */
        Gate::guessPolicyNamesUsing(function (string $class) {
            if (!in_array($class, $this->exceptedPolicies, true)) {
                return self::guessCustomisedPolicyName($class);
            }
            /** @var string $controllerClass */
            $controllerClass = get_class(Route::current()->controller);
            $classDirname = str_replace('/', '\\', dirname(str_replace('\\', '/', $controllerClass)));
            $classDirnameSegments = explode('\\', $classDirname);
            $endSegment = end($classDirnameSegments);
            $classSegment = class_basename($class);
            return ["$classDirnameSegments[0]\\Policies\\$endSegment\\{$classSegment}Policy"];
        });

        ResetPassword::createUrlUsing(function (User $user, string $token) {
            $origin = config('constant.frontend_origin');
            return "$origin/reset-password?token=$token";
        });

        VerifyEmail::createUrlUsing(function (User $user) {
            $origin = config('constant.frontend_origin');
            $baseUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );

            $path = null;
            $query = null;
            $url = parse_url($baseUrl);
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

    private static function guessCustomisedPolicyName(string $class): array
    {
        $classDirname = str_replace('/', '\\', dirname(str_replace('\\', '/', $class)));
        $classDirnameSegments = explode('\\', $classDirname);
        $endSegment = end($classDirnameSegments);
        $endSegment = $endSegment !== 'Models' ? "$endSegment\\" : '';
        $classSegment = class_basename($class);
        return ["$classDirnameSegments[0]\\Policies\\$endSegment{$classSegment}Policy"];
    }
}
