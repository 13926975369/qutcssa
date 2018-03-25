<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2017/11/18
 * Time: 20:51
 */

namespace app\qutcssa\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
}