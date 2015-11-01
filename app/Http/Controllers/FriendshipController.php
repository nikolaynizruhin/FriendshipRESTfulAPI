<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redis;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendshipController extends Controller
{
    /**
     * Pending friend request
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function pendingRequest($id)
    {
        if ($id == Auth::User()->id) {
            return abort(400, 'You can not add yourself as a friend.');
        }
        Redis::sAdd('uid:' . $id . ':requests', Auth::User()->id);
        Redis::sAdd('uid:' . Auth::User()->id . ':pendingrequests', $id);
        return response('', 201);
    }

    /**
     * Friend requests
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function requests()
    {
        $users =  User::whereIn('id', Redis::sMembers('uid:' . Auth::User()->id . ':requests'));
        return response($users->get()->toArray(), 200);
    }

    /**
     * Friend request accept
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function accept($id)
    {
        if (!Redis::sIsMember('uid:' . Auth::User()->id . ':requests', $id)) {
            return abort(400, 'No friendship request to accept.');
        }
        Redis::sAdd('uid:' . $id . ':friendslist', Auth::User()->id);
        Redis::sAdd('uid:' . Auth::User()->id . ':friendslist', $id);
        Redis::sRem('uid:' . Auth::User()->id . ':requests', $id);
        Redis::sRem('uid:' . $id . ':pendingrequests', Auth::User()->id);
        return response('', 201);
    }

    /**
     * Friend request reject
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function reject($id)
    {
        Redis::sRem('uid:' . Auth::User()->id . ':requests', $id);
        Redis::sRem('uid:' . $id . ':pendingrequests', Auth::User()->id);
        return response('', 200);
    }
}
