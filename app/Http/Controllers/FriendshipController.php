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
     * @param $userId
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function pendingRequest($userId, $id)
    {
        if ($id == $userId) {
            return abort(400, 'You can not add yourself as a friend.');
        } elseif (Redis::sIsMember('uid:' . $userId . ':friendslist', $id)) {
            return abort(400, 'Already your friend.');
        }
        Redis::sAdd('uid:' . $id . ':requests', $userId);
        Redis::sAdd('uid:' . $userId . ':pendingrequests', $id);
        return response('', 201);
    }

    /**
     * Show friend requests
     *
     * @param $userId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function requests($userId)
    {
        $friendsId = Redis::sMembers('uid:' . $userId . ':requests');
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
     * @param $userId
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function accept($userId, $id)
    {
        if (!Redis::sIsMember('uid:' . $userId . ':requests', $id)) {
            return abort(400, 'No friendship request to accept.');
        }
        Redis::sAdd('uid:' . $id . ':friendslist', $userId);
        Redis::sAdd('uid:' . $userId . ':friendslist', $id);
        Redis::sRem('uid:' . $userId . ':requests', $id);
        Redis::sRem('uid:' . $id . ':pendingrequests', $userId);
        return response('', 201);
    }

    /**
     * Friend request reject
     *
     * @param $userId
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function reject($userId, $id)
    {
        Redis::sRem('uid:' . $userId . ':requests', $id);
        Redis::sRem('uid:' . $id . ':pendingrequests', $userId);
        return response('', 200);
    }
}
