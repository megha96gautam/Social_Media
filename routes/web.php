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

/*Route for admin with prefix "admin" declared one time in group*/
Route::group(['prefix' => 'admin',  'middleware' => 'admin'], function() {
	Route::get('/dashboard','Admin\DashboardController@index');
	Route::get('/logout','Admin\DashboardController@logout');
});	

Route::group(['middleware' => 'guest'], function() {
	Route::get('/login','Admin\LoginController@index');
	Route::get('/','Admin\LoginController@index');

	Route::post('/submit_login','Admin\LoginController@submit_login');

	Route::get('/forgot_password','Admin\LoginController@forgot_password');
	Route::post('/forgot_password_submit','Admin\LoginController@forgot_password_submit');
});



