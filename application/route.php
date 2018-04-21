<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::rule('index','qutcssa/v1.Index/index','POST',['https' => true]);
Route::rule('admin','qutcssa/View/admin','GET',['https' => true]);
Route::rule('handler','qutcssa/View/all_handler','POST',['https' => true]);
Route::rule('edit','qutcssa/View/edit','GET',['https' => true]);
Route::rule('show_photo','qutcssa/View/show_photo','POST',['https' => true]);