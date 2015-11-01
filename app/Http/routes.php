<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'api', 'middleware' => 'auth.basic'], function()
{
    Route::post('me/pendingrequest/{id}', 'FriendshipController@pendingRequest')->where('id', '[0-9]+');
    Route::get('me/requests', 'FriendshipController@requests');
    Route::put('me/accept/{id}', 'FriendshipController@accept')->where('id', '[0-9]+');
    Route::delete('me/reject/{id}', 'FriendshipController@reject')->where('id', '[0-9]+');

    Route::get('me/friends', 'FriendsController@showFriends');
    Route::get('{id}/friends', 'FriendsController@showOtherFriends')->where('id', '[0-9]+');
});
