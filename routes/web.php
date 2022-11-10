<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(\App\Providers\RouteServiceProvider::HOME, fn () => response(null, 204));

if (!App::isProduction() && config('app.debug')) {
    Route::get('/', fn () => view('welcome'));
    Route::get('/phpinfo', fn () => phpinfo());
}
