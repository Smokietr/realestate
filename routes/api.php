<?php

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


//Development
Route::get('route', function () {
    dd(Route::getRoutes());
});

// Auth
Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::post('/sign-in', 'RegisterController@store')->name('register');
    Route::post('/login', 'LoginController@store')->name('login');
    Route::get('/logout', 'LoginController@logout')->name('logout');
    Route::get('/me', 'HomeController@index')->name('me');
});

Route::namespace('Calendar')->middleware('auth:sanctum')->group(function () {
    Route::resource('/calendar', 'HomeController');
});

Route::namespace('Customers')->middleware('auth:sanctum')->group(function () {
    Route::resource('/customer', 'HomeController');
});