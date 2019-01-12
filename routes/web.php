<?php

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

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home.index');
});

Route::get('/', 'HomeController@welcome')->name('home.welcome');
Route::get('email/{id}', 'EmailController@showParsed')->name('email.show-parsed');
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('email/{uuid}.gif', 'TrackingController@email')->name('email');
    Route::get('links/{uuid}', 'TrackingController@link')->name('links');
});

Auth::routes();
