<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class Chat extends IndexBase
{

    public function index()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }

        $user = Db::table("user")->where('id', session('user_id'))->find();
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
        $vipLevel = Db::table("vip_level")
            ->where('is_use','=',1)
            ->order('sort_order')
            ->select();

        $ret = [
            'user' => $user,
            'vipLevel' => $vipLevel,
        ];

        return view("index/user/index", $ret);
    }

    public function buyerStartChat()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }


    }



}
