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
// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/subscribe', function($request) {
	
});

Route::prefix('admin')->middleware('auth')->group(function() {
	Route::get('/', function () {
		return view('home');
	});
	
	Route::get('notify', function () {
		return view('admin.markers');
	});
	Route::get('chats', 'Admin\ChatsController@getAll')->name('chats');
	Route::get('users', 'Admin\UsersController@getAll')->name('users');
	Route::get('markers', 'Admin\MarkersController@getAll')->name('markers');
});

Route::middleware('guest')->group(function() {
	Route::get('/login', function() {
		return view('auth.login');
	})->name('login');
	Route::post('/login', 'Auth\LoginController@login')->name('postLogin');
});


