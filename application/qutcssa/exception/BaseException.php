<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2017/11/15
 * Time: 15:39
 */

namespace app\qutcssa\exception;


use think\Exception;

class BaseException extends Exception
{
     //  HTTP 状态码  404,200
    public $code = 400;

    //  错误具体信息
    public $msg = '参数错误';

    public function __construct($params = []){
        if (!is_array($params)){
            return;
        }

        if (array_key_exists('code',$params)){
            $this->code = $params['code'];
        }

        if (array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
    }
}