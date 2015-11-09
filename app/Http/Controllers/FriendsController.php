<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redis;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendsController extends Controller
{
    /**
     * Friends of friends
     *
     * @var
     */
    private $friendsOfFriends;

    /**
     * Current level unique friends
     *
     * @var
     */
    private $currentLevelUniqueFriends;

    /**
     * Show friends
     *
     * @param $userId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showFriends($userId)
    {
        return response(Redis::sMembers('uid:' . $userId . ':friendslist'), 200);
    }

    /**
     * Show friends of friends to N nesting level
     *
     * @param $userId
     * @param $n
     * @param int $i
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function friendsOfFriends($userId, $n, $i = 1)
    {
        if ($i == 1) {
            $this->friendsOfFriends = Redis::sMembers('uid:' . $userId . ':friendslist');
            $this->friendsOfFriends[] = $userId;
            $this->currentLevelUniqueFriends = $this->friendsOfFriends;
        } else {
            foreach ($this->currentLevelUniqueFriends as $friendId) {
                $currentLevelFriends = Redis::sMembers('uid:' . $friendId . ':friendslist');
                foreach ($currentLevelFriends as $currentLevelFriend) {
                    if (!in_array($currentLevelFriend, $this->friendsOfFriends)) {
                        $this->friendsOfFriends[] = $currentLevelFriend;
                        $uniqueFriends[] = $currentLevelFriend;
                    }
                }
            }
            if (isset($uniqueFriends)) {
                $this->currentLevelUniqueFriends = $uniqueFriends;
            }
        }
        if (empty($this->currentLevelUniqueFriends)) {
            return response($this->friendsOfFriends, 200);
        }
        if ($n == $i) {
            return response($this->friendsOfFriends, 200);
        }
        return $this->friendsOfFriends($userId, $n, $i + 1);
    }
}
