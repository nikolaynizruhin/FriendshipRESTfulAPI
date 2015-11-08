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
    Route::post('{userId}/pendingrequest/{id}', 'FriendshipController@pendingRequest')
        ->where(['userId' => '[0-9]+', 'id' => '[0-9]+']);
    Route::get('{userId}/requests', 'FriendshipController@requests')
        ->where('userId', '[0-9]+');
    Route::put('{userId}/accept/{id}', 'FriendshipController@accept')
        ->where(['userId' => '[0-9]+', 'id' => '[0-9]+']);
    Route::delete('{userId}/reject/{id}', 'FriendshipController@reject')
        ->where(['meId' => '[0-9]+', 'id' => '[0-9]+']);

    Route::get('{userId}/friends', 'FriendsController@showFriends')
        ->where('userId', '[0-9]+');
    Route::get('{userId}/friendsOfFriends/{n}', 'FriendsController@showFriendsOfFriends')
        ->where(['id' => '[0-9]+', 'n' => '[0-9]+']);
});
