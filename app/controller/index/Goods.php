<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class Goods extends IndexBase
{

    public function search()
    {
        $category_id = input('category_id');

        $where = [];
        if (!empty($category_id)) {
            $where[] = ['goods_category_id', '=', $category_id];
        }
        $count =  Db::table("goods")
            ->where($where)
            ->count();

        $goodsData = Db::table("goods")
            ->where($where)
            ->order('id','desc')
            ->paginate(1, $count, request()->param())
            ->each(function($item, $key){

                if (empty($item['goods_img'])) {
                    $item['goods_img'] = default_image();
                } else {
                    $item['goods_img'] = '/uploads/' . $item['goods_img'];
                }

                $item['nickname'] = 'think';
                return $item;
            });

        $pageShow = $goodsData->render();
//        dump($goodsData);
        $ret = [
            'goodsData' => $goodsData,
            'pageShow' => $pageShow,
        ];
//        dd($goodsData);
        return view("index/goods/search", $ret);
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
