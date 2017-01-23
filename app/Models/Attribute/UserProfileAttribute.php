<?php
/**
 * Created by PhpStorm.
 * User: dongyuxiang
 * Date: 2016/11/14
 * Time: 上午1:21
 */

namespace App\Models\Attribute;


trait UserProfileAttribute
{
    public function getNameAttribute()
    {
        if (!$this->attributes['name']) {
            return '未命名用户';
        }
        return $this->attributes['name'];
    }

    public function getAvatarAttribute()
    {
        if (!$this->attributes['avatar']) {
            return '/images/avatar.png';
        }
        return $this->attributes['avatar'];
    }
}
