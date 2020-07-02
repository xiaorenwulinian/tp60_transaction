<?php

namespace app\controller\index;


use app\common\tools\StringTool;
use think\captcha\facade\Captcha;
use think\facade\Db;


class UserFeedbackConsult extends IndexBase
{

    public function index()
    {
        if (!session('user_id')) {
            return redirect("/index/user/login");
        }
        $userId = session('user_id');

        $feedback = Db::table("user_feedback_consult")
            ->where([
                ['user_id', '=', $userId]
            ])
            ->order('id', 'desc')
            ->select();

        $ret = [

            'feedback' => $feedback,
        ];

        return view("index/user_feedback_consult/index", $ret);


    }



}
