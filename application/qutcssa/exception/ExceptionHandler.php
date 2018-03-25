<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2017/11/19
 * Time: 18:48
 */

namespace app\qutcssa\exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    // 需要返回客户端当前请求的URL路径

    public function render(\Exception $e){
        if ($e instanceof BaseException){
            //如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
        }else{
            $this->code = 500;
            $this->msg = '服务器内部错误';
            //调试
            if ($e instanceof HttpException) {
                return $this->renderHttpException($e);
            } else {
                return $this->convertExceptionToResponse($e);
            }
        }

        $result = [
            'code' => $this->code,
            'msg' => $this->msg,
        ];
        return json($result,$this->code);
    }
}
