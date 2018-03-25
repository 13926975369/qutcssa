<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2017/11/20
 * Time: 17:16
 */

namespace app\qutcssa\exception;


class BannerMissException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
}