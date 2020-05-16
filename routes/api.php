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

// Route::post('test', 'Api\AuthController@test');
// Route::post('test', 'Api\CardsController@add');
// Route::get('test', 'Api\OrdersController@test');

Route::post('login', 'Api\AuthController@login');
Route::post('recovery', 'Api\AuthController@recovery');
Route::post('recovery/confirm', 'Api\AuthController@recoveryConfirm');
Route::post('register', 'Api\AuthController@register');
Route::post('register/confirm', 'Api\AuthController@registerConfirm');

Route::group(['middleware' => 'auth:api'], function() {
	
	Route::prefix('admin')->group(function() {
		Route::prefix('markers')->group(function() {
			Route::get('getAll', 'Api\Admin\MarkersController@getAll');
			Route::get('getById', 'Api\Admin\MarkersController@getById');
			Route::post('update', 'Api\Admin\MarkersController@update');
			Route::post('create', 'Api\Admin\MarkersController@create');
			Route::delete('delete/{id}', 'Api\Admin\MarkersController@delete');
		});
		Route::prefix('users')->group(function() {
			Route::get('getAll', 'Api\Admin\UsersController@getAll');
			Route::get('getById', 'Api\Admin\UsersController@getById');
			Route::post('update', 'Api\Admin\UsersController@update');
			Route::post('create', 'Api\Admin\UsersController@create');
			Route::delete('delete/{id}', 'Api\Admin\UsersController@delete');
		});
	});
	
	Route::prefix('users')->group(function() {
		Route::get('auth', 'Api\AuthController@getUser');
		Route::get('getAll', 'Api\UsersController@getAll');
		Route::post('update', 'Api\UsersController@update');
		Route::get('getById', 'Api\UsersController@getById');
		Route::post('avatar', 'Api\UsersController@uploadAvatar');
	});
	
	Route::prefix('markers')->group(function() {
		Route::get('getAll', 'Api\MarkersController@getAll');
		Route::get('getById', 'Api\MarkersController@getById');
	});
	
	Route::prefix('orders')->group(function() {
		Route::get('getAll', 'Api\OrdersController@getAll');
		Route::post('create', 'Api\OrdersController@create');
    	Route::post('cancel', 'Api\OrdersController@cancel');
		Route::get('getById', 'Api\OrdersController@getById');
	});
});
Route::any('yandex/callback', 'Api\YandexCallbackController@webhook');
