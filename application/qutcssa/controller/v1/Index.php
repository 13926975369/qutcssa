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
use think\Db;

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
        }elseif ($type == 'A002'){
            //返回广告
            $uid = \app\qutcssa\service\Token::getCurrentUid();
            $select = Db::table('advertisement')
                ->select();

            $url = Db::table('advertisement')
                ->where([
                    'id' => 1
                ])->find();
            $url = config('setting.image_root').$url['bottom'];
            $r = [];
            $i = 0;
            $j = 0;
            foreach ($select as $v){
                $rank = (int)$v['rank'];
                if ($rank <= 3){
                    $r['up'][$i]['name'] = $v['name'];
                    if ($v['shop_id'] != ''){
                        $r['up'][$i]['shop_id'] = $v['shop_id'];
                    }
                    if ($v['rank'] == '1'){
                        $r['up'][$i]['rank'] = '一号位';
                    }elseif ($v['rank'] == '2'){
                        $r['up'][$i]['rank'] = '二号位';
                    }elseif ($v['rank'] == '3'){
                        $r['up'][$i]['rank'] = '三号位';
                    }
                    if ($v['photo'] == ''){
                        $r['up'][$i]['photo'] = '';
                    }else{
                        $r['up'][$i]['photo'] = config('setting.image_root').$v['photo'];
                    }
                    $i++;
                }else{
                    $r['down'][$j]['name'] = $v['name'];
                    if ($v['shop_id'] != ''){
                        $r['down'][$j]['shop_id'] = $v['shop_id'];
                    }
                    if ($v['rank'] == '4'){
                        $r['down'][$j]['rank'] = '一号位';
                    }elseif ($v['rank'] == '5'){
                        $r['down'][$j]['rank'] = '二号位';
                    }elseif ($v['rank'] == '6'){
                        $r['down'][$j]['rank'] = '三号位';
                    }elseif ($v['rank'] == '7'){
                        $r['down'][$j]['rank'] = '四号位';
                    }elseif ($v['rank'] == '8'){
                        $r['down'][$j]['rank'] = '五号位';
                    }
                    if ($v['photo'] == ''){
                        $r['down'][$i]['photo'] = '';
                    }else{
                        $r['down'][$j]['photo'] = config('setting.image_root').$v['photo'];
                    }
                    $j++;
                }
            }
            $r['bottom'] = $url;
            $result = json_encode([
                'code' => 200,
                'msg' => $r
            ]);
        }elseif ($type == 'A003'){
            //展示商家
            $uid = \app\qutcssa\service\Token::getCurrentUid();
            $select = Db::table('shopkeeper')
                ->order(['first' => 'asc'])
                ->select();
            $r = [];
            $i = 0;
            $j = -1;
            $flag = '';
            foreach ($select as $v){
                $shop_id = $v['id'];
                if ($flag != $v['first']){
                    $j++;
                    $i = 0;
                }
                $r[$j][$i]['letter'] = $v['first'];
                if ($v['photo'] == ''){
                    $r[$j][$i]['url'] = '';
                }else{
                    $r[$j][$i]['url'] = config('setting.image_root').$v['photo'];
                }
                $is_collect = Db::table('favour')
                    ->where([
                        'shop_id' => $shop_id,
                        'user_id' => $uid
                    ])
                    ->find();

                if ($is_collect){
                    $r[$j][$i]['is_collect'] = 1;
                }else{
                    $r[$j][$i]['is_collect'] = 0;
                }
                $r[$j][$i]['name'] = $v['username'];
                $r[$j][$i]['shop_id'] = $shop_id;
                $i++;
            }
            $result = json_encode([
                'code' => 200,
                'msg' => $r
            ]);
        }elseif ($type == 'A004'){
            //展示店家详情
            $uid = \app\qutcssa\service\Token::getCurrentUid();
            $post = input('post.');
            $this->have_key_validate([
                'data' => '无data！'
            ],$post);
            $data = $post['data'];
            $this->have_key_validate([
                'shop_id' => '无shop_id！'
            ],$data);
            $shop_id = $data['shop_id'];
            $select = Db::table('shopkeeper')
                ->where([
                    'id' => $shop_id
                ])
                ->find();
            if (!$select){
                throw new BaseException([
                    'msg' => '未找到该商家'
                ]);
            }

            $r['letter'] = $select['first'];
            if ($select['photo'] == ''){
                $r['url'] = '';
            }else{
                $r['url'] = config('setting.image_root').$select['photo'];
            }
            $r['name'] = $select['username'];
            $r['address'] = $select['address'];
            $r['type'] = $select['type'];
            $r['phone'] = $select['phone'];
            $r['to'] = config('setting.image_root').$select['to'];
            $is_collect = Db::table('favour')
                ->where([
                    'shop_id' => $shop_id,
                    'user_id' => $uid
                ])
                ->find();

            if ($is_collect){
                $r['is_collect'] = 1;
            }else{
                $r['is_collect'] = 0;
            }
            if ($select['photo'] == ''){
                $r['url'] = '';
            }else{
                $r['url'] = config('setting.image_root').$select['photo'];
            }
            if ($select['description'] == ''){
                $r['description'] = '';
            }else{
                $r['description'] = config('setting.image_root').$select['description'];
            }
            $check = Db::table('photo')
                ->where([
                    'shop_id' => $shop_id
                ])->select();
            $i = 0;
            if (!$check){
                $r['photo'] = [];
            }else{
                foreach ($check as $v){
                    $r['photo'][$i] = config('setting.image_root').$v['photo'];
                    $i++;
                }
            }

            $result = json_encode([
                'code' => 200,
                'msg' => $r
            ]);
        }elseif ($type == 'A005'){
            //展示店家详情
            $uid = \app\qutcssa\service\Token::getCurrentUid();
            $post = input('post.');
            $this->have_key_validate([
                'data' => '无data！'
            ],$post);
            $data = $post['data'];
            $this->have_key_validate([
                'type' => '无type！'
            ],$data);
            $type = $data['type'];
            $select = Db::table('shopkeeper')
                ->where([
                    'type' => $type
                ])
                ->select();
            if (!$select){
                throw new BaseException([
                    'msg' => '未找到该类别商家'
                ]);
            }

            $r = [];
            $i = 0;
            $j = -1;
            $flag = '';
            foreach ($select as $v){
                $shop_id = $v['id'];
                if ($flag != $v['first']){
                    $j++;
                    $i = 0;
                }
                $r[$j][$i]['letter'] = $v['first'];
                if ($v['photo'] == ''){
                    $r[$j][$i]['url'] = '';
                }else{
                    $r[$j][$i]['url'] = config('setting.image_root').$v['photo'];
                }
                $r[$j][$i]['name'] = $v['username'];
                $r[$j][$i]['shop_id'] = $v['id'];
                $is_collect = Db::table('favour')
                    ->where([
                        'shop_id' => $shop_id,
                        'user_id' => $uid
                    ])
                    ->find();

                if ($is_collect){
                    $r[$j][$i]['is_collect'] = 1;
                }else{
                    $r[$j][$i]['is_collect'] = 0;
                }
                $i++;
            }

            $result = json_encode([
                'code' => 200,
                'msg' => $r
            ]);
        }elseif ($type == 'A006'){
            if ($token != 'qutcssa'){
                throw new BaseException([
                    'msg' => '传入的身份验证不正确！'
                ]);
            }
            $this->have_key_validate([
                'code' => '无code！',
                'name' => '无name！',
                'sex' => '无sex！',
                'birthday' => '无birthday！',
                'student_no' => '无student_no！',
                'email' => '无email！'
            ],$data);
            if ($data['code'] == ''){
                throw new BaseException([
                    'msg' => 'code不能为空！'
                ]);
            }
            $result = $tokenModel->getToken2($data['code'],[
                'name' => $data['name'],
                'sex' => $data['sex'],
                'birthday' => $data['birthday'],
                'student_no' => $data['student_no'],
                'email' => $data['email']
            ]);
        }elseif ($type == 'A007'){
            $uid = \app\qutcssa\service\Token::getCurrentUid();
            $post = input('post.');
            $this->have_key_validate([
                'data' => '无data！'
            ],$post);
            $data = $post['data'];
            $this->have_key_validate([
                'shop_id' => '无shop_id！'
            ],$data);
            $good_id = $data['shop_id'];

            $check = Db::table('favour')
                ->where([
                    'shop_id' => $good_id,
                    'user_id' => $uid
                ])->find();
            if ($check){
                $del = Db::table('favour')
                    ->where([
                        'shop_id' => $good_id,
                        'user_id' => $uid
                    ])->delete();
                if (!$del){
                    throw new BaseException([
                        'msg' => '取消收藏失败'
                    ]);
                }
            }else{
                $result = Db::table('favour')
                    ->insert([
                        'shop_id' => $good_id,
                        'user_id' => $uid,
                        'time' => (int)time()
                    ]);
                if (!$result){
                    throw new BaseException([
                        'msg' => '收藏失败'
                    ]);
                }
            }

            return json_encode([
                'code' => 200,
                'msg' => '收藏成功'
            ]);
        }elseif ($type == 'A008'){
            $uid = \app\qutcssa\service\Token::getCurrentUid();
            $post = input('post.');
            $this->have_key_validate([
                'data' => '无data！'
            ],$post);

            $check = Db::table('type')
                ->field('type')
                ->select();
            if (!$check){
                throw new BaseException([
                    'msg' => '暂无分类'
                ]);
            }

            return json_encode([
                'code' => 200,
                'msg' => $check
            ]);
        }elseif ($type == 'A009'){
            $uid = \app\qutcssa\service\Token::getCurrentUid();
            $post = input('post.');
            $this->have_key_validate([
                'data' => '无data！'
            ],$post);

            $check = Db::table('favour')
                ->where([
                    'user_id' => $uid
                ])
                ->select();
            if (!$check){
                throw new BaseException([
                    'msg' => '暂无收藏商家'
                ]);
            }
            $r = [];
            $i = 0;
            $j = -1;
            $flag = '';
            foreach ($check as $v){
                $shop_id = $v['shop_id'];
                $select = Db::table('shopkeeper')
                    ->where([
                        'id' => $shop_id
                    ])
                    ->find();
                if (!$select){
                    throw new BaseException([
                        'msg' => '未找到该类别商家'
                    ]);
                }
                if ($flag != $select['first']){
                    $j++;
                    $i = 0;
                }
                $r[$j][$i]['letter'] = $select['first'];
                if ($select['photo'] == ''){
                    $r[$j][$i]['url'] = '';
                }else{
                    $r[$j][$i]['url'] = config('setting.image_root').$select['photo'];
                }
                $r[$j][$i]['name'] = $select['username'];
                $r[$j][$i]['shop_id'] = $shop_id;

                $i++;
            }

            return json_encode([
                'code' => 200,
                'msg' => $r
            ]);
        }
//        elseif ($type == 'A010'){
//            vendor('phpqrcode.phpqrcode');
//            $url = 'https://www.yiluzou.cn/Fishot/public/static/song/qinghua.m4a';
//            $errorCorrectionLevel = 'L';//容错级别
//            $matrixPointSize = 6;//生成图片大小
//            $new_image = COMMON_PATH.'static/test3.png';
//            //生成二维码图片
//            \QRcode::png($url, $new_image, $errorCorrectionLevel, $matrixPointSize, 2);
//            //输出图片
//            header("Content-type: image/png");
//            return json([
//                'code' => 200,
//                'msg' => config('setting.image_root').'static/test3.png'
//            ]);
//            $uid = \app\qutcssa\service\Token::getCurrentUid();

//        }
        else{
            throw new BaseException([
                'msg' => '输入类型有误！'
            ]);
        }
        return $result;
    }
}