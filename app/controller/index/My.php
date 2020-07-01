<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class My extends IndexBase
{

    /**
     * 我发布的商品
     * @return \think\response\Redirect|\think\response\View
     */
    public function publishLst()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        $userId = session('user_id');

        $publishGoods = Db::table('goods')
//            ->where('publish_type','=', 2)
//            ->where('publish_id','=',$userId)
            ->select()
            ->toArray();

        foreach ($publishGoods as &$v) {
            if (empty($v['goods_img'])) {
                $v['goods_img'] = default_image();
            } else {
                $v['goods_img'] = '/uploads/' . $v['goods_img'];
            }
        }
//        dump($publishGoods);

        $user = Db::table("user")->where('id', $userId)->find();
        $deadline = $user['vip_deadline'];
        if ($deadline < time()) {
            $deadline = 0;
        } else {
            $gapDay = ceil(($deadline - time()) / (3600 * 24));
            if ($gapDay < 31) {
                $deadline = $gapDay . '天';
            } else {
                $gapMonth = ceil($gapDay / 30);
                $year = floor($gapMonth / 12);
                $month = $gapMonth % 12;

                $deadlineYear = $deadlineMonth = '';
                if (!empty($year)) {
                    $deadlineYear = $year . "年";
                }

                if ($year < 10) {
                    if (!empty($month)) {
                        $deadlineMonth = $month . "个月";
                    }
                }

                $deadline = $deadlineYear . $deadlineMonth;
            }
        }
        $user['deadline_day'] = $deadline;
//        dump($user);

        $ret = [
            'user' => $user,

            'publishGoods' => $publishGoods,
        ];

        return view('index/my/publish_lst', $ret);

    }


    /**
     * 我发布的商品
     * @return \think\response\Redirect|\think\response\View
     */
    public function publishAdd()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        $userId = session('user_id');

        $publishGoods = Db::table('goods')
//            ->where('publish_type','=', 2)
//            ->where('publish_id','=',$userId)
            ->select()
            ->toArray();

        foreach ($publishGoods as &$v) {
            if (empty($v['goods_img'])) {
                $v['goods_img'] = default_image();
            } else {
                $v['goods_img'] = '/uploads/' . $v['goods_img'];
            }
        }
        dump($publishGoods);

        $user = Db::table("user")->where('id', $userId)->find();
        $deadline = $user['vip_deadline'];
        if ($deadline < time()) {
            $deadline = 0;
        } else {
            $gapDay = ceil(($deadline - time()) / (3600 * 24));
            if ($gapDay < 31) {
                $deadline = $gapDay . '天';
            } else {
                $gapMonth = ceil($gapDay / 30);
                $year = floor($gapMonth / 12);
                $month = $gapMonth % 12;

                $deadlineYear = $deadlineMonth = '';
                if (!empty($year)) {
                    $deadlineYear = $year . "年";
                }

                if ($year < 10) {
                    if (!empty($month)) {
                        $deadlineMonth = $month . "个月";
                    }
                }

                $deadline = $deadlineYear . $deadlineMonth;
            }
        }
        $user['deadline_day'] = $deadline;
//        dump($user);
        $category = \app\model\GoodsCategory::where('is_show','=',1)
            ->column('cate_name','id');

        $ret = [
            'user' => $user,
            'publishGoods' => $publishGoods,
            'category' => $category,
        ];

        return view('index/my/publish_add', $ret);

    }

}
