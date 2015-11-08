<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redis;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendsController extends Controller
{
    /**
     * Show friends
     *
     * @param $userId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function showFriends($userId)
    {
        $friendsIds = Redis::sMembers('uid:' . $userId . ':friendslist');
        return $this->getFriends($friendsIds) ? $this->getFriends($friendsIds) : '';
    }

    /**
     * Show friends of friends to N nesting level
     *
     * @param $userId
     * @param $n
     * @param int $i
     * @return string
     */
    public function showFriendsOfFriends($userId, $n, $i = 1)
    {
        if ($i == 1) {
            Redis::sAdd('nestingLevel:' . $i . ':friendslist', Redis::sMembers('uid:' . $userId . ':friendslist'));
            Redis::sAdd('friendsOfFriends', Redis::sMembers('uid:' . $userId . ':friendslist'));
            Redis::sAdd('friendsOfFriends', $userId);
        } else {
            $previousLevel = $i - 1;
            $previousLevelFriends = Redis::sMembers('nestingLevel:' . $previousLevel . ':friendslist');
            Redis::del('nestingLevel:' . $previousLevel . ':friendslist');
            foreach ($previousLevelFriends as $previousLevelFriend) {
                Redis::sAdd('currentLevelFriends', Redis::sMembers('uid:' . $previousLevelFriend . ':friendslist'));
            }
            $currentLevelFriends = Redis::sMembers('currentLevelFriends');
            Redis::del('currentLevelFriends');
            foreach ($currentLevelFriends as $currentLevelFriend) {
                if (!Redis::sIsMember('friendsOfFriends', $currentLevelFriend)) {
                    Redis::sAdd('nestingLevel:' . $i . ':friendslist', $currentLevelFriend);
                    Redis::sAdd('friendsOfFriends', $currentLevelFriend);
                }
            }
        }
        if (!Redis::sMembers('nestingLevel:' . $i . ':friendslist')) {
            $friendsIds = $this->getFriendsOfFriends($userId);
            $this->removeTemporarySets($i);
            return $this->getFriends($friendsIds) ? $this->getFriends($friendsIds) : '';
        }
        if ($i == $n) {
            $friendsIds = $this->getFriendsOfFriends($userId);
            $this->removeTemporarySets($i);
            return $this->getFriends($friendsIds);
        }
        return $this->showFriendsOfFriends($userId, $n, $i + 1);
    }

    /**
     * Get friends info
     *
     * @param $ids
     * @return string
     */
    private function getFriends($ids)
    {
        if ($ids) {
            foreach ($ids as $id) {
                $friends[$id] = Redis::hGetAll('uid:' . $id . ':info');
            }
            return $friends;
        } else return '';
    }

    /**
     * Get friends of friends ids
     *
     * @param $userId
     * @return array
     */
    private function getFriendsOfFriends($userId)
    {
        Redis::sRem('friendsOfFriends', $userId);
        return Redis::sMembers('friendsOfFriends');
    }

    /**
     * Remove temporary redis sets
     *
     * @param $i
     */
    private function removeTemporarySets($i)
    {
        Redis::del('friendsOfFriends');
        Redis::del('nestingLevel:' . $i . ':friendslist');
    }
}
