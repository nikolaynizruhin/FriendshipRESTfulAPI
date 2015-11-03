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

Route::group(['prefix' => 'api'], function()
{
    Route::post('{meId}/pendingrequest/{id}', 'FriendshipController@pendingRequest')
        ->where(['meId' => '[0-9]+', 'id' => '[0-9]+']);
    Route::get('{meId}/requests', 'FriendshipController@requests')
        ->where('meId', '[0-9]+');
    Route::put('{meId}/accept/{id}', 'FriendshipController@accept')
        ->where(['meId' => '[0-9]+', 'id' => '[0-9]+']);
    Route::delete('{meId}/reject/{id}', 'FriendshipController@reject')
        ->where(['meId' => '[0-9]+', 'id' => '[0-9]+']);

    Route::get('{meId}/friends', 'FriendsController@showFriends')
        ->where('meId', '[0-9]+');
});
