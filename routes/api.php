<?php

use App\Http\Controllers\Common\InvitationController;
use App\Http\Controllers\Common\LocaleController;
use App\Http\Controllers\Common\MyUserController;
use App\Http\Controllers\Common\PermissionController;
use App\Http\Controllers\Common\RoleController;
use App\Http\Controllers\Common\StatusController;
use App\Http\Controllers\Common\TokenController;
use App\Http\Controllers\Common\UserController;
use App\Http\Controllers\Division\DivisionController;
use App\Http\Controllers\Division\MemberController;
use App\Http\Controllers\Sample\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function () {
    $limiter = config('fortify.limiters.login');
    Route::post('/login', [TokenController::class, 'storeToken'])
        ->middleware(array_filter(['guest', $limiter ? 'throttle:' . $limiter : null]));
    Route::post('/logout', [TokenController::class, 'destroyToken'])
        ->middleware('auth:sanctum');

    Route::get('/locales', LocaleController::class);
    Route::get('/status', StatusController::class);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [MyUserController::class, 'index']);
        Route::patch('/user', [MyUserController::class, 'store']);
        Route::apiResource('users', UserController::class)->except(['store']);
        Route::apiResource('roles', RoleController::class)->only(['index']);
        Route::apiResource('permissions', PermissionController::class)->only(['index']);
        Route::apiResource('invitations', InvitationController::class);

        // FIXME: SAMPLE CODE
        Route::apiResource('divisions', DivisionController::class);
        Route::apiResource('divisions.members', MemberController::class);
        Route::apiResource('divisions.projects', ProjectController::class);
        Route::post('/divisions/{division}/projects/create-async', [ProjectController::class, 'storeAsync']);
        Route::patch(
            '/divisions/{division}/projects/{project}/update-async',
            [ProjectController::class, 'updateAsync']
        );
    });
});
