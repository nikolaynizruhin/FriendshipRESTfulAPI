<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redis;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendsController extends Controller
{
    /**
     * Friends list
     *
     * @param $userId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showFriends($userId)
    {
        $friendsId = Redis::sMembers('uid:' . $userId . ':friendslist');
        if ($friendsId) {
            foreach ($friendsId as $friendId) {
                $friendsList[$friendId] = Redis::hGetAll('uid:' . $friendId . ':info');
            }
            return response($friendsList, 200);
        } else return response('', 200);
    }
}
