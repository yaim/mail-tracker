<?php

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

Route::middleware('auth:api')->group(function () {
    Route::resource('email', 'EmailController')->except([
        'create', 'edit', 'update', 'destroy',
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'Auth\TokenLoginController@login')->name('api.auth.login');

    Route::middleware('auth:api')->group(function () {
        Route::get('user', 'Auth\TokenLoginController@user')->name('api.auth.user');
        Route::post('logout', 'Auth\TokenLoginController@logout')->name('api.auth.logout');
    });
});
