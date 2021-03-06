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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\AuthController@login');
    // Route::post('signup', 'Auth\AuthController@signup');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'Auth\AuthController@user');
    Route::get('logout', 'Auth\AuthController@logout');
});
