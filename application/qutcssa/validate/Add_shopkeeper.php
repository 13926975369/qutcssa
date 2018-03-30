<?php
/**
 * Created by PhpStorm.
 * User: 63254
 * Date: 2018/3/30
 * Time: 11:26
 */

namespace app\qutcssa\validate;


class Add_shopkeeper extends BaseValidate
{
    protected $rule = [
        'username' => 'require',
        'address' => 'require',
        'phone' => 'require',
    ];

    protected $message = [
        'username.require' => '商铺名字不能为空！',
        'address.require' => '商铺地址不能为空！',
        'phone.require' => '商铺电话不能为空！',
    ];

    protected $field = [
        'username' => '商铺名字',
        'address' => '商铺地址',
        'phone' => '商铺电话',
    ];
}