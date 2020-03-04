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

Route::post('test', 'Api\AuthController@test');
Route::post('login', 'Api\AuthController@login');
Route::post('recovery', 'Api\AuthController@recovery');
Route::post('recovery/confirm', 'Api\AuthController@recoveryConfirm');
Route::post('register', 'Api\AuthController@register');
Route::post('register/confirm', 'Api\AuthController@registerConfirm');

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('user', 'Api\AuthController@getUser');
});
