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
        Route::any('topUp', 'index.User/topUp'); // 充值
        Route::any('withdraw', 'index.User/withdraw'); // 提现

        Route::get('feedbackConsult/index', 'index.User/feedbackConsultIndex'); // 意见反馈列表
        Route::any('feedbackConsult/add', 'index.User/feedbackConsultAdd'); // 意见反馈列表


        Route::any('buyer/lst', 'index.User/buyerLst'); // 买家列表
        Route::any('seller/lst', 'index.User/sellerLst'); // 卖家列表



    });

    /**
     * 我是买家
     */
    Route::group('buyer', function () {

        Route::get('order/index', 'index.buyer/orderIndex'); // 买家订单列表
        Route::get('order/chat/index', 'index.buyer/orderChatIndex'); // 买家订单聊天列表
        Route::post('order/chat/add', 'index.buyer/orderChatAdd'); // 买家订单聊天添加

    });

    /**
     * 好友聊天
     */
    Route::group('friendChat', function () {

        Route::get('index', 'index.friendChat/index'); // 聊天室
        Route::get('indexBuyer', 'index.friendChat/indexBuyer'); // 买家聊天室
        Route::get('indexSeller', 'index.friendChat/indexSeller'); // 卖家聊天室

        Route::get('indexMore', 'index.friendChat/indexMore'); // 加载更多
        Route::post('addHouse', 'index.friendChat/addHouse'); // 买家开房
        Route::post('chat/add', 'index.friendChat/addChatContent'); // 买家开房
    });


    /**
     * 我是卖家
     */
    Route::group('seller', function () {

        Route::get('order/index', 'index.seller/orderIndex'); // 卖家订单列表
        Route::get('order/chat/index', 'index.seller/orderChatIndex'); // 卖家订单聊天列表
        Route::post('order/chat/add', 'index.seller/orderChatAdd'); // 卖家订单聊天添加



    });


    Route::group('goods', function () {
        Route::any('search', 'index.goods/search'); // 商品列表
        Route::any('category', 'index.goods/category'); // 商品分类
        Route::get('detail', 'index.goods/detail');  // 商品详情

        Route::any('publishLst', 'index.goods/publishLst'); // 发布商品列表
        Route::any('publishAdd', 'index.goods/publishAdd'); // 发布商品添加
        Route::post('publishAddStore', 'index.goods/publishAddStore'); // 发布商品添加
        Route::any('publishEdit', 'index.goods/publishEdit'); // 发布商品修改
        Route::any('publishEditStore', 'index.goods/publishEditStore'); // 发布商品修改

    });

    Route::group('order', function () {
        Route::any('buy', 'index.order/buy'); // 下单
        Route::any('openVip', 'index.order/openVip'); // 下单


    });

    Route::group('my', function () {

    });

    /**
     * 测试
     */
    Route::group('test', function () {
        Route::any('index', 'index.test/index'); //


    });



})->middleware([think\middleware\SessionInit::class]);



