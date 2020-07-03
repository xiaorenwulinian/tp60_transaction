<?php

use think\facade\Route;



Route::group('/admin', function(){
    Route::group('', function () {
        Route::get('test', function () {
            return 'hello,admin!';
        });
        Route::get('', 'admin.Index/index');
        Route::get('index/mainContent', 'admin.Index/mainContent');

        Route::group('goodsCategory', function () {
            Route::get('lst', 'admin.GoodsCategory/lst');
        });

    })->middleware([app\middleware\AdminAuthCheck::class]);
//    });

    // 商品分类，暂未使用
    Route::group('goodsCategory', function () {
        Route::get('lst', 'admin.GoodsCategory/lst');
        Route::any('add', 'admin.GoodsCategory/add');
        Route::any('edit', 'admin.GoodsCategory/edit');
        Route::any('changeShow', 'admin.GoodsCategory/changeShow');
        Route::any('delete', 'admin.GoodsCategory/delete');
        Route::any('editSort', 'admin.GoodsCategory/editSort');
    });

    // vip级别
    Route::group('vipLevel', function () {
        Route::get('lst', 'admin.vipLevel/lst');
        Route::any('add', 'admin.vipLevel/add');
        Route::any('edit', 'admin.vipLevel/edit');
        Route::any('changeShow', 'admin.vipLevel/changeShow');
        Route::any('editSort', 'admin.vipLevel/editSort');

    });

    // 交易记录
    Route::group('transactionRecord', function () {
        Route::get('lst', 'admin.transactionRecord/lst');
        Route::any('add', 'admin.vipLevel/add');
        Route::any('edit', 'admin.vipLevel/edit');
        Route::any('changeShow', 'admin.vipLevel/changeShow');
        Route::any('editSort', 'admin.vipLevel/editSort');

    });

    // 用户
    Route::group('user', function () {
        Route::get('lst', 'admin.User/lst');
        Route::any('add', 'admin.User/add');
        Route::any('edit', 'admin.User/edit');
        Route::any('changeShow', 'admin.User/changeShow');
        Route::any('editSort', 'admin.User/editSort');
        Route::any('topUp', 'admin.User/topUp');

        // 意见反馈
        Route::get('feedbackConsult/lst', 'admin.User/feedbackConsultLst');
        Route::post('feedbackConsult/add', 'admin.User/feedbackConsultAdd');


    });


    // 基本配置
    Route::group('baseConfig', function () {
//        Route::get('lst', 'admin.BaseConfig/lst');
        Route::get('lst', 'admin.BaseConfig/edit');
        Route::any('edit', 'admin.BaseConfig/edit');
    });






    // 商品
    Route::group('goods', function () {
        Route::get('lst', 'admin.Goods/lst');
//        Route::any('add', 'admin.Goods/add');
        Route::any('edit', 'admin.Goods/edit');
        Route::any('changeShow', 'admin.Goods/changeShow');
        Route::any('delete', 'admin.Goods/delete');
        Route::any('editSort', 'admin.Goods/editSort');
        Route::any('exportData', 'admin.Goods/exportData');
        Route::any('editMultiFileDelete', 'admin.Goods/editMultiFileDelete');
        Route::post('auditChange', 'admin.Goods/auditChange'); // 商品审核

    });

    Route::group('common', function () {
        Route::any('addUpload', 'admin.Common/addUpload');  // 添加时上传文件
        Route::any('addMultiUpload', 'admin.Common/addMultiUpload');  // 添加时上传文件
        Route::any('editUpload', 'admin.Common/editUpload');  // 修改时上传文件
        Route::any('addDeleteFile', 'admin.Common/addDeleteFile'); // 添加时删除文件
    });


    // 广告分类
    Route::group('advertiseType', function () {
        Route::get('lst', 'admin.AdvertiseType/lst');
        Route::any('add', 'admin.AdvertiseType/add');
        Route::any('edit', 'admin.AdvertiseType/edit');
        Route::any('changeShow', 'admin.AdvertiseType/changeShow');
        Route::any('delete', 'admin.AdvertiseType/delete');
        Route::any('editSort', 'admin.AdvertiseType/editSort');
        Route::any('exportData', 'admin.AdvertiseType/exportData');
    });

    // 广告
    Route::group('advertise', function () {
        Route::get('lst', 'admin.Advertise/lst');
        Route::any('add', 'admin.Advertise/add');
        Route::any('edit', 'admin.Advertise/edit');
        Route::any('changeShow', 'admin.Advertise/changeShow');
        Route::any('delete', 'admin.Advertise/delete');
        Route::any('editSort', 'admin.Advertise/editSort');
        Route::any('exportData', 'admin.Advertise/exportData');
    });

    // 鲜花用途
    Route::group('flowerUse', function () {
        Route::get('lst', 'admin.FlowerUse/lst');
        Route::any('add', 'admin.FlowerUse/add');
        Route::any('edit', 'admin.FlowerUse/edit');
        Route::any('changeShow', 'admin.FlowerUse/changeShow');
        Route::any('delete', 'admin.FlowerUse/delete');
        Route::any('editSort', 'admin.FlowerUse/editSort');
    });

    // 鲜花花材
    Route::group('flowerMaterial', function () {
        Route::get('lst', 'admin.FlowerMaterial/lst');
        Route::any('add', 'admin.FlowerMaterial/add');
        Route::any('edit', 'admin.FlowerMaterial/edit');
        Route::any('changeShow', 'admin.FlowerMaterial/changeShow');
        Route::any('delete', 'admin.FlowerMaterial/delete');
        Route::any('editSort', 'admin.FlowerMaterial/editSort');
        Route::any('exportData', 'admin.FlowerMaterial/exportData');
    });



    Route::any('login/login', 'admin.Login/login');

    Route::get('login/logout', 'admin.Login/logout');
    Route::get('login/verify', 'admin.Login/verify');

})->middleware([think\middleware\SessionInit::class]);




