<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2017/11/15
 * Time: 14:44
 */
return [
    //第一条是我的，第二条是龙传自己的
    'app_id' => 'wx395873e79d5ed1ab',
//    'app_id' => 'wx5c7d4bf385825667',
    'app_secret' => '08d9dc7206bc6bae8341b7d30c12f4ae',
//    'app_secret' => '6b03ddfe5068ef3a3a4ab66e254c5a31',
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?"."appid=%s&secret=%s&js_code=%s&grant_type=authorization_code"
];