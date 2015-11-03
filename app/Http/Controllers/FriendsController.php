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
     * @param $meId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showFriends($meId)
    {
        $friendsId = Redis::sMembers('uid:' . $meId . ':friendslist');
        if ($friendsId) {
            foreach ($friendsId as $friendId) {
                $friendsList[$friendId] = Redis::hGetAll('uid:' . $friendId . ':info');
            }
            return response($friendsList, 200);
        } else return response('', 200);
    }
}
