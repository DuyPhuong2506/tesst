<?php

use Illuminate\Http\Request;

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

Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwtAuth'], function () {
    Route::get('user-info', 'UsersController@getUserCurrent');
    Route::post('auth/logout', 'AuthController@logout');
});

Route::get('agora/get-token','AgoraController@generateToken');
