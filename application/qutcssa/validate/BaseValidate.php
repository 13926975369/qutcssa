<?php
namespace app\qutcssa\validate;


use app\qutcssa\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /*
     * 构造简便check方法
     * $result = $validate->batch()->check($date);
     * 首先获得数据，然后调用验证层验证数据
     * */
    public function  goCheck(){
        $request = Request::instance();
        $params = $request->param();

        $result = $this->batch()->check($params);
        if(!$result){
            throw new ParameterException([
                'msg' => $this->error,
            ]);
        }else{
            return true;
        }
    }

    public function goToCheck($data){
        $result = $this->batch()->check($data);
        if(!$result){
            throw new ParameterException([
                'msg' => $this->error,
            ]);
        }else{
            return true;
        }
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = ''){
        if (empty($value)){
            return false;
        }else{
            return true;
        }
    }

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = ''){
        if (is_numeric($value) && is_int($value+0) && ($value+0) > 0){
            return true;
        }else{
            return $field.'必须是正整数';
        }
    }

    protected function MustBeNumber($value, $rule = '', $data = '', $field = ''){
        if (preg_match("/^[0-9]+$/",$value)){
            return true;
        }else{
            return $field.'必须是数字';
        }
    }
}