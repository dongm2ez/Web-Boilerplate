<?php
/**
 * Created by PhpStorm.
 * User: dongyuxiang
 * Date: 2016/11/14
 * Time: 上午1:19
 */

namespace App\Models\Attribute;


use App\Models\UserFollow;
use Auth;

trait UserAttribute
{
    public function getFollowedAttribute()
    {
        if (Auth::check()) {
            if (Auth::id() == $this->id) {
                return true;
            }
            return UserFollow::where([
                'user_id' => Auth::id(),
                'follower_id' => $this->id
            ])->exists();
        }
        return false;
    }
}