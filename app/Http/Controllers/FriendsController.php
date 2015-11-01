<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redis;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendsController extends Controller
{
    /**
     * Friends list
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showFriends()
    {
        $users =  User::whereIn('id', Redis::sMembers('uid:' . Auth::User()->id . ':friendslist'));
        return response($users->get()->toArray(), 200);
    }

    /**
     * Friends list of user
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showOtherFriends($id)
    {
        $users =  User::whereIn('id', Redis::sMembers('uid:' . $id . ':friendslist'));
        return response($users->get()->toArray(), 200);
    }
}
