<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get(\App\Providers\RouteServiceProvider::HOME, fn () => response(null, 204));

if (!App::isProduction() && config('app.debug')) {
    Route::get('/', fn () => view('welcome'));
    Route::get('/phpinfo', fn () => phpinfo());
}
