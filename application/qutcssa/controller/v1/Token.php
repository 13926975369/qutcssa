<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2018/3/23
 * Time: 19:29
 */

namespace app\qutcssa\controller\v1;
use app\qutcssa\exception\BaseException;
use app\qutcssa\service\Token as TokenService;
use app\qutcssa\service\UserToken;

class Token extends BaseException
{
    public function getToken($code='') {
        $ut = new UserToken($code);
        $token = $ut->get();
        $msg = [
            'token' => $token
        ];
        return json_encode([
            'msg' => $msg,
            'code' => 200,
        ]);
    }

    public function verifyToken($token=''){
        if (!$token){
            throw new BaseException([
                'token不允许为空'
            ]);
        }
        $Token = new TokenService();
        $valid = $Token->verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }
}