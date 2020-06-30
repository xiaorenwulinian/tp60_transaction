<?php

namespace app\controller\index;


use think\facade\Db;
use think\facade\View;

class Index extends IndexBase
{
    public function index()
    {
        $uid = session('user_id');
        if (empty($uid)) {
            $uid = 0;
            $userData = [];
        } else {
            $userData = \app\model\User::where("id", '=', $uid)->find();
        }

        $category = Db::table('goods_category')
            ->where('is_show', '=', 1)
            ->select()
            ->toArray();



        foreach ($category as &$v) {
            $goods = DB::table("goods")
                ->where('goods_category_id','=', $v['id'])
                ->limit(4)
                ->select()
                ->toArray();
            foreach ($goods as &$good) {
                $img = $good['goods_img'];
                if (empty($img)) {
                    $good['goods_img'] = default_image();
                } else {
                    $good['goods_img'] = '/uploads/' . $img;
                }
            }
            $v['goods_info'] = $goods;
        }
//        dump($category);

        $ret = [
            'userIndexData' => $userData,
            'uid' => $uid,
            'category' => $category,
        ];


        return \view('index/index/index', $ret);
    }





}
