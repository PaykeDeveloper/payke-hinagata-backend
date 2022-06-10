<?php

namespace App\Providers;

use App\Actions\Common\CreateNewUserFromInvitation;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUserFromInvitation::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->get(config('fortify.username')) . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        /**
         * APIログインの場合に、PrepareAuthenticatedSession::class を使用しない。
         *
         * @see \Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::loginPipeline
         */
        Fortify::authenticateThrough(function (Request $request) {
            /** @var Route $route */
            $route = $request->route();
            /** @var array $middleware */
            $middleware = $route->middleware();
            $enableSession = !in_array('api', $middleware, true);
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                Features::enabled(Features::twoFactorAuthentication()) ?
                    RedirectIfTwoFactorAuthenticatable::class : null,
                AttemptToAuthenticate::class,
                $enableSession ? PrepareAuthenticatedSession::class : null,
            ]);
        });
    }
}
