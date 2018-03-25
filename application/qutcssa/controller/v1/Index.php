<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2018/3/23
 * Time: 14:34
 */
namespace app\qutcssa\controller\v1;
use app\qutcssa\controller\BaseController;
use app\qutcssa\exception\BaseException;

class Index extends BaseController
{
    public function index(){
        define('a','token');
        define('b','type');
        define('c','data');
        $post = input('post.');
        $this->have_key_validate([
            a => '无身份标识！',
            b => '无接口类型!',
            c => '没有data参数!'
        ],$post);
        $token = $post[a];
        $type = $post[b];
        $data = $post[c];

        $tokenModel = new Token();
        //定义result
        $result = [];
        if ($type == 'A001'){
            if ($token != 'qutcssa'){
                throw new BaseException([
                    'msg' => '传入的身份验证不正确！'
                ]);
            }
            $this->have_key_validate([
                'code' => '无code！'
            ],$data);
            if ($data['code'] == ''){
                throw new BaseException([
                    'msg' => 'code不能为空！'
                ]);
            }
            $result = $tokenModel->getToken($data['code']);
        }else{
            throw new BaseException([
                'msg' => '输入类型有误！'
            ]);
        }
        return $result;
    }
}