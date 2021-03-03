<?php

use App\Http\Controllers\Sample\BookCommentController;
use App\Http\Controllers\Sample\BookController;
use App\Http\Controllers\StatusController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/status', StatusController::class);
    Route::middleware('auth:sanctum')->group(function () {
        // FIXME: サンプルコードです。
        Route::apiResource('books', BookController::class);
        Route::apiResource('books.comments', BookCommentController::class);
        Route::post('/books/{book}/comments/create-async', [BookCommentController::class, 'storeAsync']);
        Route::patch('/books/{book}/comments/{comment}/update-async', [BookCommentController::class, 'updateAsync']);
    });
});
