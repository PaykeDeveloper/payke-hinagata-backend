<?php

use App\Http\Controllers\Sample\BookImportCsvController;
use App\Http\Controllers\Sample\BookCommentController;
use App\Http\Controllers\Sample\BookController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TokenController;
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
    Route::get('/status', StatusController::class);
    Route::middleware('auth:sanctum')->group(function () {
        // FIXME: SAMPLE CODE
        Route::apiResource('books', BookController::class);
        Route::apiResource('books.comments', BookCommentController::class);
        Route::post('/books/{book}/comments/create-async', [BookCommentController::class, 'storeAsync']);
        Route::patch('/books/{book}/comments/{comment}/update-async', [BookCommentController::class, 'updateAsync']);
        Route::resource('/csv-upload/books', BookImportCsvController::class)
            ->names('books-importer')
            ->except(['edit', 'update', 'destroy']);
    });
});
