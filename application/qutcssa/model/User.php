<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2018/3/23
 * Time: 19:34
 */

namespace app\qutcssa\model;


class User extends BaseModel
{
    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)
            ->find();
        return $user;
    }
}