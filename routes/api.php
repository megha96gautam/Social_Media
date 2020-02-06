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
	/*Gmail and FB login*/
	//Route::post('/sociallogin', 'Api\SocialAuthGoogleController@loginRegister');

	/*login*/
	Route::post('login', 'Api\UserController@login');

	/*registration*/
	Route::post('user_register', 'Api\UserController@userregister');

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

	/*update user profile*/
	Route::post('getuserdetails', 'Api\UserController@getuserdetails');

	/*block user*/
	Route::post('blockuser', 'Api\UserController@blockuser');

	/*search user*/
	Route::post('searchuser', 'Api\UserController@searchuser');

	/*get user list*/
	Route::get('getuserlist', 'Api\UserController@getuserlist');

	/*get block user list*/
	Route::post('getblockuserlist', 'Api\UserController@getblockuserlist');
	
	/*follow user request*/
	Route::post('sendfollowrequest', 'Api\FollowController@sendfollowrequest');	

	/*get followers list*/
	Route::post('followerlist', 'Api\FollowController@getfollowerslist');	

	/*get following list*/
	Route::post('followinglist', 'Api\FollowController@getfollowinglist');

	/*get follow request*/
	Route::post('getfollowrequests', 'Api\FollowController@getFollowRequest');

	/*create group*/
	Route::post('creategroup', 'Api\GroupController@creategroup');	

	/*create group*/
	Route::post('getgroupdetails', 'Api\GroupController@getgroupdetails');	

	/*add group member*/
	Route::post('addgroupmember', 'Api\GroupController@addgroupmember');		
	/*remove group member*/
	Route::post('removegroupmember', 'Api\GroupController@removegroupmember');	

	/*exit group*/
	Route::post('exitgroup', 'Api\GroupController@exitgroup');

	/*group users list*/
	Route::post('groupuserlist', 'Api\GroupController@groupuserlist');

	/*get friend list*/
	Route::post('getfriendlist', 'Api\FriendController@getfriendlist');

	/*private dob*/
	Route::post('privatedob', 'Api\UserController@privatedob');

	/*private dob*/
	Route::post('checkuserblockstatus', 'Api\UserController@checkuserblockstatus');

	/*create feed*/
	Route::post('createfeed', 'Api\FeedController@createfeed');
	
	/*edit feed text*/
	Route::post('editfeedtext', 'Api\FeedController@editfeedtext');

	/*get user feed list*/
	Route::post('getUserFeedlist', 'Api\FeedController@getUserFeedlist');	

	/*delete feed*/
	Route::post('deletefeed', 'Api\FeedController@deletefeed');	

	/*get user feed list*/
	Route::post('getFollowFeedlist', 'Api\FeedController@getFollowFeedlist');

	/*get user image gallarey list*/
	Route::post('imagegallery', 'Api\FeedController@getImageGallarey');

	/*get user video gallarey list*/
	Route::post('videogallery', 'Api\FeedController@getVideoGallarey');

	/*get notification listing*/
	Route::post('getnotificationlisting', 'Api\NotificationController@getNotificationListing');
				
});