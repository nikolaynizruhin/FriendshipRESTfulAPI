<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redis;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendshipController extends Controller
{
    /**
     * Pending friend request
     *
     * @param $meId
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function pendingRequest($meId, $id)
    {
        if ($id == $meId) {
            return abort(400, 'You can not add yourself as a friend.');
        } elseif (Redis::sIsMember('uid:' . $meId . ':friendslist', $id)) {
            return abort(400, 'Already your friend.');
        }
        Redis::sAdd('uid:' . $id . ':requests', $meId);
        Redis::sAdd('uid:' . $meId . ':pendingrequests', $id);
        return response('', 201);
    }

    /**
     * Friend requests
     *
     * @param $meId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function requests($meId)
    {
        $friendsId = Redis::sMembers('uid:' . $meId . ':requests');
        if ($friendsId) {
            foreach ($friendsId as $friendId) {
                $friendsList[$friendId] = Redis::hGetAll('uid:' . $friendId . ':info');
            }
            return response($friendsList, 200);
        } else return response('', 200);
    }

    /**
     * Friend request accept
     *
     * @param $meId
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function accept($meId, $id)
    {
        if (!Redis::sIsMember('uid:' . $meId . ':requests', $id)) {
            return abort(400, 'No friendship request to accept.');
        }
        Redis::sAdd('uid:' . $id . ':friendslist', $meId);
        Redis::sAdd('uid:' . $meId . ':friendslist', $id);
        Redis::sRem('uid:' . $meId . ':requests', $id);
        Redis::sRem('uid:' . $id . ':pendingrequests', $meId);
        return response('', 201);
    }

    /**
     * Friend request reject
     *
     * @param $meId
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function reject($meId, $id)
    {
        Redis::sRem('uid:' . $meId . ':requests', $id);
        Redis::sRem('uid:' . $id . ':pendingrequests', $meId);
        return response('', 200);
    }
}
