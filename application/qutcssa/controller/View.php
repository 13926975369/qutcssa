<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2018/3/30
 * Time: 15:39
 */

namespace app\qutcssa\controller;


use app\qutcssa\exception\UpdateException;
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
            $phone = $post['phone'];
            $type = $post['type'];
            $first = $post['first'];
            $to = $post['to'];
            Db::startTrans();
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

                $description = Request::instance()->file('description');
                if ($description){
                    //如果有图片的话就上传
                    //验证上传格式
                    $info = $description->validate(['ext'=>'jpg,jpeg,png,bmp,gif'])->move('upload');
                    $description_url = '';
                    if ($info && $info->getPathname()){
                        $description_url .= $info->getPathname();
                    }else{
                        echo "<script>alert('图片上传失败！')</script>";
                        echo "<script>window.history.go(-1)</script>";
                    }
                    $result = Db::table('shopkeeper')->insertGetId([
                        'username' => $username,
                        'photo' => $url,
                        'address' => $address,
                        'description' => $description_url,
                        'type' => $type,
                        'first' => $first,
                        'phone' => $phone
                    ]);
                    vendor('phpqrcode.phpqrcode');
                    $u = $to;
                    $errorCorrectionLevel = 'L';//容错级别
                    $matrixPointSize = 6;//生成图片大小
                    $new_image = COMMON_PATH.'static/'.$result.'.png';
                    //生成二维码图片
                    \QRcode::png($u, $new_image, $errorCorrectionLevel, $matrixPointSize, 2);
                    //输出图片
                    header("Content-type: image/png");
                    $result2 = Db::table('shopkeeper')
                        ->where([
                            'id' => $result
                        ])
                        ->update([
                        'to' => 'static/'.$result.'.png'
                    ]);
                }else{
                    $result = Db::table('shopkeeper')->insertGetId([
                        'username' => $username,
                        'photo' => $url,
                        'address' => $address,
                        'type' => $type,
                        'first' => $first,
                        'phone' => $phone
                    ]);
                    vendor('phpqrcode.phpqrcode');
                    $u = $to;
                    $errorCorrectionLevel = 'L';//容错级别
                    $matrixPointSize = 6;//生成图片大小
                    $new_image = COMMON_PATH.'static/'.$result.'.png';
                    //生成二维码图片
                    \QRcode::png($u, $new_image, $errorCorrectionLevel, $matrixPointSize, 2);
                    //输出图片
                    header("Content-type: image/png");
                    $result2 = Db::table('shopkeeper')
                        ->where([
                            'id' => $result
                        ])
                        ->update([
                            'to' => 'static/'.$result.'.png'
                        ]);
                }
            }else{
                $description = Request::instance()->file('description');
                if ($description){
                    //如果有图片的话就上传
                    //验证上传格式
                    $info = $description->validate(['ext'=>'jpg,jpeg,png,bmp,gif'])->move('upload');
                    $description_url = '';
                    if ($info && $info->getPathname()){
                        $description_url .= $info->getPathname();
                    }else{
                        echo "<script>alert('图片上传失败！')</script>";
                        echo "<script>window.history.go(-1)</script>";
                    }
                    $result = Db::table('shopkeeper')->insertGetId([
                        'username' => $username,
                        'address' => $address,
                        'description' => $description_url,
                        'type' => $type,
                        'first' => $first,
                        'phone' => $phone
                    ]);
                    vendor('phpqrcode.phpqrcode');
                    $u = $to;
                    $errorCorrectionLevel = 'L';//容错级别
                    $matrixPointSize = 6;//生成图片大小
                    $new_image = COMMON_PATH.'static/'.$result.'.png';
                    //生成二维码图片
                    \QRcode::png($u, $new_image, $errorCorrectionLevel, $matrixPointSize, 2);
                    //输出图片
                    header("Content-type: image/png");
                    $result2 = Db::table('shopkeeper')
                        ->where([
                            'id' => $result
                        ])
                        ->update([
                            'to' => 'static/'.$result.'.png'
                        ]);
                }else{
                    $result = Db::table('shopkeeper')->insertGetId([
                        'username' => $username,
                        'address' => $address,
                        'type' => $type,
                        'first' => $first,
                        'phone' => $phone
                    ]);
                    vendor('phpqrcode.phpqrcode');
                    $u = $to;
                    $errorCorrectionLevel = 'L';//容错级别
                    $matrixPointSize = 6;//生成图片大小
                    $new_image = COMMON_PATH.'static/'.$result.'.png';
                    //生成二维码图片
                    \QRcode::png($u, $new_image, $errorCorrectionLevel, $matrixPointSize, 2);
                    //输出图片
                    header("Content-type: image/png");
                    $result2 = Db::table('shopkeeper')
                        ->where([
                            'id' => $result
                        ])
                        ->update([
                            'to' => 'static/'.$result.'.png'
                        ]);
                }
            }
            if (!$result || !$result2){
                Db::rollback();
                echo "<script>alert('更新失败！')</script>";
                echo "<script>window.history.go(-1)</script>";
            }

            $file=Request::instance()->file('photo');
            if($file){
                foreach($file as $v){
                    $info=$v->move('upload');
                    if ($info && $info->getPathname()){
                        $uu = $info->getPathname();

                        $c = Db::table('photo')
                            ->insert([
                                'photo' => $uu,
                                'shop_id' => $result
                            ]);
                        if (!$c){
                            Db::rollback();
                            echo "<script>alert('图片上传失败！')</script>";
                            echo "<script>window.history.go(-1)</script>";
                        }
                    }else{
                        Db::rollback();
                        echo "<script>alert('图片上传失败！')</script>";
                        echo "<script>window.history.go(-1)</script>";
                    }
                }
            }
            Db::commit();

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
        }elseif($t == 'edit'){
            $name = $post['name'];
            $id = $post['id'];
            $check = Db::table('shopkeeper')
                ->where([
                    'id' => $name
                ])->find();
            if ($name == ''){
                $name = NULL;
            }else{
                if (!$check){
                    echo "<script>alert('没有这个商铺序号！')</script>";
                    echo "<script>window.history.go(-1)</script>";
                    exit();
                }
            }
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
                    exit();
                }
                $result = Db::table('advertisement')
                    ->where([
                        'id' => $id
                    ])
                    ->update([
                    'shop_id' => $name,
                    'photo' => $url,
                    'rank' => $id
                ]);
                if (!$result){
                    echo "<script>alert('更新内容与之前一致！')</script>";
                    echo "<script>window.history.go(-1)</script>";
                    exit();
                }
            }else{
                $result = Db::table('advertisement')
                    ->where([
                        'id' => $id
                    ])
                    ->update([
                        'shop_id' => $name,
                        'rank' => $id
                    ]);
                if (!$result){
                    echo "<script>alert('更新内容与之前一致！')</script>";
                    echo "<script>window.history.go(-1)</script>";
                    exit();
                }
            }
            echo "<script>alert('成功！')</script>";
            echo "<script>window.history.go(-1)</script>";
        }else{
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
                    exit();
                }
                $result = Db::table('advertisement')
                    ->where([
                        'id' => 1
                    ])
                    ->update([
                        'bottom' => $url
                    ]);
                if (!$result){
                    echo "<script>alert('更新失败！')</script>";
                    echo "<script>window.history.go(-1)</script>";
                    exit();
                }
            }

            echo "<script>alert('成功！')</script>";
            echo "<script>window.history.go(-1)</script>";
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
                    $result[$k]['logo'] = "<a href='".config('setting.image_root').$result[$k]['photo']."'>点击查看</a>";
                }else{
                    $result[$k]['logo'] = '无';
                }

                if ($v['description'] != ''){
                    $result[$k]['description'] = "<a href='".config('setting.image_root').$result[$k]['description']."'>点击查看</a>";
                }else{
                    $result[$k]['description'] = '无';
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

    public function manager_advertisement(){
        $result = Db::table('advertisement')
            ->select();
        $a = [];
        $result1 = [];
        if (!$result){
            $result = [];
            $result1 = [];
        }else{
            foreach ($result as $k => $v){
                $shop_id = $v['shop_id'];
                $s = Db::table('shopkeeper')
                    ->where([
                        'id' => $shop_id
                    ])
                    ->find();
                if ($k <= 2){

                    if ($v['photo'] != ''){
                        $a[$k]['photo'] = "<a href='".config('setting.image_root').$result[$k]['photo']."'>点击查看</a>";
                    }else{
                        $a[$k]['photo'] = '无';
                    }
                    $a[$k]['url'] = $result[$k]['url'];
                    if ($result[$k]['rank'] == '1'){
                        $a[$k]['rank'] = '一号位';
                    }elseif ($result[$k]['rank'] == '2'){
                        $a[$k]['rank'] = '二号位';
                    }elseif ($result[$k]['rank'] == '3'){
                        $a[$k]['rank'] = '三号位';
                    }
                    $a[$k]['name'] = $s['username'];
                    $a[$k]['edit'] = config('setting.domain')."edit?id=".$result[$k]['id'];
                }else{
                    if ($v['photo'] != ''){
                        $result1[$k]['photo'] = "<a href='".config('setting.image_root').$result[$k]['photo']."'>点击查看</a>";
                    }else{
                        $result1[$k]['photo'] = '无';
                    }
                    $result1[$k]['url'] = $result[$k]['url'];
                    if ($result[$k]['rank'] == '4'){
                        $result1[$k]['rank'] = '一号位';
                    }elseif ($result[$k]['rank'] == '5'){
                        $result1[$k]['rank'] = '二号位';
                    }elseif ($result[$k]['rank'] == '6'){
                        $result1[$k]['rank'] = '三号位';
                    }elseif ($result[$k]['rank'] == '7'){
                        $result1[$k]['rank'] = '四号位';
                    }elseif ($result[$k]['rank'] == '8'){
                        $result1[$k]['rank'] = '五号位';
                    }
                    $result1[$k]['name'] = $s['username'];
                    $result1[$k]['edit'] = config('setting.domain')."edit?id=".$result[$k]['id'];
                }
            }
        }
        $this->assign('result',$a);
        $this->assign('result1',$result1);
        return $this->fetch();
    }

    public function edit(){
        $id = input('get.id');
        $a = [];
        if ((int)$id <= 2){
            $a[0]['name'] = '一号位';
            $a[0]['rank'] = '1';
            $a[1]['name'] = '二号位';
            $a[1]['rank'] = '2';
            $a[2]['name'] = '三号位';
            $a[2]['rank'] = '3';
        }else{
            $a[3]['name'] = '一号位';
            $a[3]['rank'] = '4';
            $a[4]['name'] = '二号位';
            $a[4]['rank'] = '5';
            $a[5]['name'] = '三号位';
            $a[5]['rank'] = '6';
            $a[5]['name'] = '四号位';
            $a[5]['rank'] = '7';
            $a[5]['name'] = '五号位';
            $a[5]['rank'] = '8';
        }
        $this->assign('id',$id);
        $this->assign('a',$a);
        return $this->fetch();
    }

    public function show_photo(){
        $id = input('post.id');
        $result = Db::table('photo')
            ->where([
                'shop_id' => $id
            ])->select();
        if (!$result){
            echo "无图片";
        }else{
            foreach ($result as $v){
                $photo = config('setting.image_root').$v['photo'];
                echo "<img src='".$photo."'>";
                echo "<br>";
            }
        }
    }

    public function manager_custom(){
        return $this->fetch();
    }
}