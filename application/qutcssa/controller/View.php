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
        return $this->fetch();
    }

    public function all_handler(){
        $post = input('post.');
        $t = $post['t'];
        if ($t == 'add_shopkeeper'){
            (new Add_shopkeeper())->goCheck();
            $username = $post['username'];
            $address = $post['address'];
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
                    echo "<script>alert('图片上传失败！')</script>";
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

            echo "<script>alert('成功！')</script>";
            echo "<script>window.history.go(-1)</script>";
        }elseif ($t == "del_shopkeeper"){
            $id = $post['id'];
            $result = Db::table('shopkeeper')
                ->where([
                    'id' => $id
                ])
                ->delete();
            if (!$result){
                return json([
                    'code' => 400,
                    'msg' => '删除失败'
                ]);
            }else{
                return json([
                    'code' => 200,
                    'msg' => 'success'
                ]);
            }
        }elseif ($t == "del_shoptype"){
            $id = $post['id'];
            $result = Db::table('type')
                ->where([
                    'id' => $id
                ])
                ->delete();
            if (!$result){
                return json([
                    'code' => 400,
                    'msg' => '删除失败'
                ]);
            }else{
                return json([
                    'code' => 200,
                    'msg' => 'success'
                ]);
            }
        }elseif ($t == "add_type"){
            $name = $post['name'];
            $result = Db::table('type')
                ->insert([
                    'type' => $name
                ]);
            if (!$result){
                echo "<script>alert('添加失败！')</script>";
                echo "<script>window.history.go(-1)</script>";
            }else{
                echo "<script>alert('成功！')</script>";
                echo "<script>window.history.go(-1)</script>";
            }
        }
    }

    public function manager_shopkeeper(){
        $result = Db::table('shopkeeper')
            ->select();
        if (!$result){
            $result = [];
        }else{
            foreach ($result as $k => $v){
                if ($v['photo'] != ''){
                    $result[$k]['photo'] = "<a href='".config('setting.image_root').$result[$k]['photo']."'>点击查看</a>";
                }else{
                    $result[$k]['photo'] = '无';
                }
            }
        }
        $this->assign('result',$result);
        return $this->fetch();
    }

    public function manager_shoptype(){
        $result = Db::table('type')
            ->select();
        if (!$result){
            $result = [];
        }
        $this->assign('result',$result);
        return $this->fetch();
    }
}