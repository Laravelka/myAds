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


Route::post('login', 'Api\AuthController@login');
Route::post('verify', 'Api\AuthController@verify');
Route::post('recovery', 'Api\AuthController@recovery');
Route::post('recovery/confirm', 'Api\AuthController@recoveryConfirm');
Route::post('register', 'Api\AuthController@register');

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('user', 'Api\AuthController@getUser');
});
