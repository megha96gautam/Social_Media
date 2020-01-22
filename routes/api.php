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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'checkHeader'], function () {
	
	/*login*/
	Route::post('login', 'Api\UserController@login');

	/*registration*/
	Route::post('register', 'Api\UserController@register');

	/*otp verification*/
	Route::post('verifyotp', 'Api\UserController@verifyotp');

	/*forgot password*/
	Route::post('forgotpassword', 'Api\UserController@forgotpassword');

	/*change password*/
	Route::post('changepassword', 'Api\UserController@changepassword');

	/*resend otp*/
	Route::post('resendotp', 'Api\UserController@resendotp');

	/*update user profile*/
	Route::post('updateprofile', 'Api\UserController@updateprofile');


});