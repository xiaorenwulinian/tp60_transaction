<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('/h', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('/', 'index.index/index');


Route::group('index', function(){

    Route::get('/', 'index.index/index');

    // 用户
    Route::group('user', function () {
        Route::any('index', 'index.User/index');
        Route::any('login', 'index.User/login');
        Route::get('logout', 'index.User/logout');
        Route::get('verify', 'index.User/verify');
        Route::any('register', 'index.User/register');

    });





})->middleware([think\middleware\SessionInit::class]);



