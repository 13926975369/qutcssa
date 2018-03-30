<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2018/3/30
 * Time: 15:39
 */

namespace app\qutcssa\controller;


use app\qutcssa\validate\Add_shopkeeper;
use think\Db;
use think\Request;

class View extends BaseController
{

    public function main(){
        return $this->fetch();
    }

    public function admin(){
        return $this->fetch();
    }

    public function add_shopkeeper(){
        //查出有多少类型
        $result = Db::table('type')->select();
        if (!$result){
            $a[0]['type'] = '无';
            $result = $a;
        }
        $this->assign('result',$result);
        $this->assign('js_path',COMMON_PATH.'static/jquery-3.2.1.min.js');
        $js_path = COMMON_PATH.'jquery-3.2.1.min.js';
        return $this->fetch();
    }

    public function all_handler(){
        $post = input('post.');
        $t = $post['t'];
        if ($t == 'add_shopkeeper'){
            (new Add_shopkeeper())->goCheck();
            $username = $post['username'];
            $address = $post['address'];
            $phone = $post['phone'];
            $description = $post['phone'];
            $phone = $post['phone'];
            $type = $post['type'];
            $first = $post['first'];
            //上传头像
            $photo = Request::instance()->file('img');
            if ($photo){
                //如果有图片的话就上传
                //验证上传格式
                $info = $photo->validate(['ext'=>'jpg,jpeg,png,bmp,gif'])->move('upload');
                $url = '';
                if ($info && $info->getPathname()){
                    $url .= $info->getPathname();
                }else{
                    echo "<script>alert('图片上传有误！')</script>";
                    echo "<script>window.history.go(-1)</script>";
                }
                $result = Db::table('shopkeeper')->insert([
                    'username' => $username,
                    'photo' => $url,
                    'address' => $address,
                    'description' => $description,
                    'type' => $type,
                    'first' => $first,
                    'phone' => $phone
                ]);
            }else{
                $result = Db::table('shopkeeper')->insert([
                    'username' => $username,
                    'address' => $address,
                    'description' => $description,
                    'type' => $type,
                    'first' => $first,
                    'phone' => $phone
                ]);
            }
            if (!$result){
                echo "<script>alert('更新失败！')</script>";
                echo "<script>window.history.go(-1)</script>";
            }
        }
    }
}