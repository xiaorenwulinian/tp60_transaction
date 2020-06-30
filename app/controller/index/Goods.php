<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class Goods extends IndexBase
{

    public function index()
    {
        $category_id = input('category_id');

        $goodsData = Db::table("goods")

//            ->where('goods_category_id', '=', $category_id)
            ->select();

//        dump($goodsData);
        $ret = [
            'goodsData' => $goodsData
        ];
//        dd($goodsData);
        return view("index/goods/index", $ret);
    }

    /**
     * 分类列表
     */
    public function topUp()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }
        return view("index/user/top_up");
    }

    /**
     * 详情
     */
    public function detail()
    {
        $goodsId = input('goods_id');

        $goodsData = Db::table("goods")
            ->where('id', '=', $goodsId)
            ->find();

//        dump($goodsData);
        $ret = [
            'goodsData' => $goodsData
        ];
        return view("index/goods/detail", $ret);
    }

}
